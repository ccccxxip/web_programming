-- создание базы данных
CREATE DATABASE IF NOT EXISTS cosmetics;

-- выбор базы данных
USE cosmetics;

-- таблицы

-- категории товаров
CREATE TABLE Categories (
    category_id INT AUTO_INCREMENT PRIMARY KEY,
    category_name VARCHAR(50) NOT NULL
);

-- товары магазина
CREATE TABLE Products (
    product_id INT AUTO_INCREMENT PRIMARY KEY,
    product_name VARCHAR(100) NOT NULL,
    category_id INT,
    brand VARCHAR(50),
    price DECIMAL(10,2),
    quantity INT,

    -- связь с таблицей категорий
    FOREIGN KEY (category_id)
        REFERENCES Categories(category_id)
        ON DELETE SET NULL
        ON UPDATE CASCADE
);

-- клиенты магазина
CREATE TABLE Customers (
    customer_id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    phone VARCHAR(20)
);

-- таблица заказов
CREATE TABLE Orders (
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT,
    order_date DATE,
    status VARCHAR(30),

    -- связь с клиентом
    FOREIGN KEY (customer_id)
        REFERENCES Customers(customer_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

-- какие товары входят в заказ
CREATE TABLE OrderItems (
    item_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT,
    product_id INT,
    quantity INT,

    -- связь с заказами
    FOREIGN KEY (order_id)
        REFERENCES Orders(order_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    -- связь с товарами
    FOREIGN KEY (product_id)
        REFERENCES Products(product_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

-- тестовые данные 

-- категории товаров
INSERT INTO Categories(category_name)
VALUES
('Кремы'),
('Помады'),
('Тушь'),
('Шампуни'),
('Тональные средства'),
('Пудра'),
('Скрабы'),
('Маски для лица'),
('Сыворотки'),
('Парфюм');

-- товары
INSERT INTO Products(product_name, category_id, brand, price, quantity) 
VALUES 
('Увлажняющий крем',1,'Celimax',1015,20), 
('Матовая помада',2,'Shu',850,15), 
('Тушь для ресниц',3,'BelorDesign',300,12), 
('Шампунь восстанавливающий',4,'Loreal',2200,18), 
('Тональный крем',5,'Erborian',1856,10), 
('Рассыпчатая пудра',6,'ManlyPRO',1989,14), 
('Энзимная пудра',7,'The U',451,22), 
('Тканевая маска',8,'Darling',234,40), 
('Сыворотка с витамином C',9,'The Ordinary',1350,8), 
('Парфюм женский',10,'Dior',20650,5);

-- клиенты
INSERT INTO Customers(full_name, phone)
VALUES
('Иван Петров', '+79991112233'),
('Анна Сидорова', '+79994445566'),
('Мария Иванова', '+79993334455'),
('Дмитрий Смирнов', '+79992221100'),
('Елена Кузнецова', '+79995556677'),
('Алексей Федоров', '+79998887766'),
('Ольга Васильева', '+79990001122'),
('Сергей Попов', '+79997778899'),
('Наталья Морозова', '+79994443322'),
('Артем Волков', '+79996665544');

-- заказы
INSERT INTO Orders(customer_id, order_date, status)
VALUES
(1, '2026-06-01', 'Оформлен'),
(2, '2026-06-02', 'Выполнен'),
(3, '2026-06-03', 'Оформлен'),
(4, '2026-06-03', 'В обработке'),
(5, '2026-06-04', 'Оформлен'),
(6, '2026-06-05', 'Выполнен'),
(7, '2026-06-05', 'Оформлен'),
(8, '2026-06-06', 'В обработке'),
(9, '2026-06-06', 'Оформлен'),
(10, '2026-06-07', 'Выполнен');

-- состав заказов
INSERT INTO OrderItems(order_id, product_id, quantity)
VALUES
(1,1,1),
(1,2,2),
(2,3,1),
(3,4,1),
(4,5,2),
(5,6,1),
(6,7,3),
(7,8,5),
(8,9,1),
(9,10,1);


-- представление
CREATE VIEW OrderInfo AS
SELECT
    o.order_id,
    c.full_name,
    p.product_name,
    oi.quantity,
    (oi.quantity * p.price) AS total_price, -- вычисляемое поле 
    o.order_date,
    o.status
FROM Orders o
JOIN Customers c ON o.customer_id = c.customer_id -- кому принадлежит заказ
JOIN OrderItems oi ON o.order_id = oi.order_id -- какие товары входят в заказ
JOIN Products p ON oi.product_id = p.product_id; -- название товара


-- индексы

-- поиск товаров по категории
CREATE INDEX idx_products_category
ON Products(category_id);
-- пример использования 
SELECT *
FROM Products
WHERE category_id = 1;


-- поиск заказов по клиенту
CREATE INDEX idx_orders_customer
ON Orders(customer_id);
-- пример использования 
SELECT *
FROM Orders
WHERE customer_id = 1;


-- триггер
DELIMITER //

CREATE TRIGGER after_orderitem_insert
AFTER INSERT ON OrderItems
FOR EACH ROW -- для каждой записи отдельно работает 
BEGIN
    UPDATE Products
    SET quantity = quantity - NEW.quantity
    WHERE product_id = NEW.product_id; -- в новой записи 
END //

DELIMITER ;



-- процедура
DELIMITER //

CREATE PROCEDURE CreateOrder(
    IN p_customer_id INT -- входное значение 
)
BEGIN
    INSERT INTO Orders(customer_id, order_date, status)
    VALUES (p_customer_id, CURDATE(), 'Оформлен');
END //

DELIMITER ;
--пример использования
CALL CreateOrder(1); 



-- операции

-- добавление нового товара
INSERT INTO Products(product_name, category_id, brand, price, quantity)
VALUES ('Бальзам для губ',2,'Nivea',350,25);

-- изменение цены товара
UPDATE Products
SET price = 700
WHERE product_id = 1;

-- удаление товара
DELETE FROM Products
WHERE product_id = 3;

-- создание нового заказа
INSERT INTO Orders(customer_id, order_date, status)
VALUES (1, CURDATE(), 'Оформлен');

-- добавление товара в заказ
INSERT INTO OrderItems(order_id, product_id, quantity)
VALUES (1,1,2);

-- изменение статуса заказа
UPDATE Orders
SET status = 'В обработке'
WHERE order_id = 1;

UPDATE Orders
SET status = 'Выполнен'
WHERE order_id = 1;