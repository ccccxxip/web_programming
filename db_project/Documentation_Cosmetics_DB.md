# База данных магазина косметики

Позволяет:
- хранить информацию о товарах
- вести учет категорий товаров
- хранить информацию о клиентах
- оформлять заказы
- отслеживать состав заказов
- контролировать статус выполнения заказов

## 1. Структура базы данных


* `Categories` — категории товаров
* `Products` — товары
* `Customers` — клиенты
* `Orders` — заказы
* `OrderItems` — состав заказов

---

## 1.1 Таблица Categories

Хранит список категорий товаров.

### Поля таблицы

* `category_id` — идентификатор категории;
* `category_name` — название категории.

### Код создания таблицы

```sql
CREATE TABLE Categories (
    category_id INT AUTO_INCREMENT PRIMARY KEY,
    category_name VARCHAR(50) NOT NULL
);
```


---

## 1.2 Таблица Products

Хранит информацию о товарах магазина.

### Поля таблицы

* `product_id` — идентификатор товара
* `product_name` — название
* `category_id` — категория
* `brand` — бренд
* `price` — цена
* `quantity` — количество

### Код создания таблицы

```sql
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
```


## 1.3 Таблица Customers

Информация о клиентах

### Поля таблицы

* `customer_id` — идентификатор клиента
* `full_name` — ФИО
* `phone` — номер телефона

### Код создания таблицы

```sql
CREATE TABLE Customers (
    customer_id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    phone VARCHAR(20)
);
```

---

## 1.4 Таблица Orders

Хранит информацию о заказах

### Поля таблицы

* `order_id` — идентификатор заказа
* `customer_id` — клиент
* `order_date` — дата заказа
* `status` — статус заказа

### Код создания таблицы

```sql
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
```


## 1.5 Таблица OrderItems

Состав каждого заказа

### Поля таблицы

* `item_id` — идентификатор записи
* `order_id` — номер заказа
* `product_id` — товар
* `quantity` — количество

### Код создания таблицы

```sql
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
```


## 2. Связи между таблицами

### Categories -> Products

Связаны по полю category_id. Одна категория может содержать несколько товаров

### Customers -> Orders 

Связаны по полю customer_id. Один клиент может оформить несколько заказов

### Orders -> OrderItems

Связаны по полю order_id. Один заказ может содержать несколько товаров

### Products -> OrderItems

Связаны по полю product_id. Один товар может входить в разные заказы

---

## 3. Представление (VIEW)

Для удобного просмотра информации о заказах

Оно объединяет данные и показывает:

* номер заказа
* клиента
* товар
* количество
* стоимость
* дату заказа
* статус заказа

### Код представления

```sql
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
```

---

## 4. Индексы 
`Для ускорения поиска данных`

### Поиск товаров по категории

```sql
CREATE INDEX idx_products_category
ON Products(category_id);
```

Пример использования:

```sql
SELECT *
FROM Products
WHERE category_id = 1;
```

### Поиск заказов клиента

```sql
CREATE INDEX idx_orders_customer
ON Orders(customer_id);
```

Пример использования:

```sql
SELECT *
FROM Orders
WHERE customer_id = 1;
```

---

## 5. Триггер

`Создан триггер, который автоматически уменьшает количество товара при добавлении товара в заказ`

### Код триггера

```sql
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
```

---

## 6. Процедура

`Процедура для создания нового заказа клиента`

Процедура принимает идентификатор клиента и автоматически создаёт новый заказ.

### Код процедуры

```sql
DELIMITER //

CREATE PROCEDURE CreateOrder(
    IN p_customer_id INT -- входное значение 
)
BEGIN
    INSERT INTO Orders(customer_id, order_date, status)
    VALUES (p_customer_id, CURDATE(), 'Оформлен');
END //

DELIMITER ;
```

Пример использования:

```sql
CALL CreateOrder(1);
```

---

## 7. Тестовые данные

Для проверки работы бд были добавлены:

* 10 категорий товаров
* 10 товаров
* 10 клиентов
* 10 заказов
* 10 записей состава заказов

Примеры заполнения таблиц:

```sql
INSERT INTO Categories(category_name)
VALUES
('Кремы'),
('Помады'),
('Тушь');
```

```sql
INSERT INTO Customers(full_name, phone)
VALUES
('Иван Петров', '+79991112233');
```

---

## 8. Типовые операции

### Добавление товара

```sql
INSERT INTO Products(product_name, category_id, brand, price, quantity)
VALUES ('Бальзам для губ',2,'Nivea',350,25);
```

### Изменение информации о товаре

```sql
UPDATE Products
SET price = 700
WHERE product_id = 1;
```

### Удаление товара

```sql
DELETE FROM Products
WHERE product_id = 3;
```

### Создание заказа

```sql
INSERT INTO Orders(customer_id, order_date, status)
VALUES (1, CURDATE(), 'Оформлен');
```

### Добавление товара в заказ

```sql
INSERT INTO OrderItems(order_id, product_id, quantity)
VALUES (1,1,2);
```

### Изменение статуса заказа

```sql
UPDATE Orders
SET status = 'В обработке'
WHERE order_id = 1;
```

### Выполнение заказа

```sql
UPDATE Orders
SET status = 'Выполнен'
WHERE order_id = 1;
```

---

## 9. Вывод

Была разработана реляционная бд магазина косметики

Созданы таблицы, связи между ними, представление, индексы, триггер и процедура. Бд позволяет хранить информацию о товарах, клиентах, заказах и выполнять операции магазина
