document.addEventListener('DOMContentLoaded', function() {
    // Константы по ТЗ
    const GRID_COLS = 9;
    const GRID_ROWS = 6;
    const TILE_SIZE = 50;
    const PUZZLE_WIDTH = 450;
    const PUZZLE_HEIGHT = 300;
    const TOTAL_PIECES = GRID_COLS * GRID_ROWS;
    
    // Элементы
    const piecesContainer = document.getElementById('pieces-container');
    const puzzleContainer = document.getElementById('puzzle-container');
    const previewImg = document.getElementById('preview-img');
    const startBtn = document.getElementById('start-btn');
    const resetBtn = document.getElementById('reset-btn');
    const timeElement = document.getElementById('time');
    const movesElement = document.getElementById('moves');
    const messageElement = document.getElementById('message');
    const progressElement = document.getElementById('progress');
    
    // Состояние игры
    let gameState = {
        pieces: [],
        puzzleGrid: Array(GRID_ROWS).fill().map(() => Array(GRID_COLS).fill(null)),
        time: 0,
        timer: null,
        moves: 0,
        isPlaying: false,
        draggedPiece: null
    };
    
    startBtn.addEventListener('click', function() {
        if (!gameState.isPlaying) {
            initGame();
            gameState.isPlaying = true;
            startBtn.disabled = true;
            startBtn.textContent = "Игра идет...";
            messageElement.textContent = "Перетаскивайте кусочки на поле";
        }
    });
    
    resetBtn.addEventListener('click', function() {
        resetGame();
    });
    
    function initGame() {
        resetGame();
        
        createPieces();
        
        createPuzzleGrid();
        
        startTimer();
        
        console.log(`Игра инициализирована: ${GRID_COLS}×${GRID_ROWS} = ${TOTAL_PIECES} кусочков`);
    }
    
    function resetGame() {
        piecesContainer.innerHTML = '';
        puzzleContainer.innerHTML = '';
        
        gameState.pieces = [];
        gameState.puzzleGrid = Array(GRID_ROWS).fill().map(() => Array(GRID_COLS).fill(null));
        gameState.time = 0;
        gameState.moves = 0;
        gameState.isPlaying = false;
        gameState.draggedPiece = null;
        
        if (gameState.timer) {
            clearInterval(gameState.timer);
        }
        
        timeElement.textContent = '00:00';
        movesElement.textContent = '0';
        progressElement.textContent = '0%';
        messageElement.textContent = 'Нажмите "Начать игру"';
        startBtn.disabled = false;
        startBtn.textContent = "Начать игру";
    }
    
    function createPieces() {
        let indices = [];
        for (let i = 0; i < TOTAL_PIECES; i++) indices.push(i);
        shuffleArray(indices);
        
        console.log(`Создаем ${TOTAL_PIECES} кусочков пазла...`);

        for (let i = 0; i < TOTAL_PIECES; i++) {
            const index = indices[i];
            const row = Math.floor(index / GRID_COLS);
            const col = index % GRID_COLS;
            
            const piece = document.createElement('div');
            piece.className = 'piece';
            piece.dataset.index = index;
            piece.dataset.row = row;
            piece.dataset.col = col;
            
            piece.style.backgroundImage = `url('${previewImg.src}')`;
            piece.style.backgroundPosition = `-${col * TILE_SIZE}px -${row * TILE_SIZE}px`;
            
            piece.draggable = true;
            piece.addEventListener('dragstart', dragStart);
            piece.addEventListener('dragend', dragEnd);
            
            piecesContainer.appendChild(piece);

            gameState.pieces.push({
                element: piece,
                index: index,
                row: row,
                col: col,
                placed: false
            });
        }
        
        console.log(`Кусочки созданы: ${gameState.pieces.length} шт.`);
    }
    
    // Создание игрового поля 9×6
    function createPuzzleGrid() {
        console.log(`Создаем игровое поле ${GRID_COLS}×${GRID_ROWS}...`);
        
        for (let row = 0; row < GRID_ROWS; row++) {
            for (let col = 0; col < GRID_COLS; col++) {
                const cell = document.createElement('div');
                cell.className = 'puzzle-cell';
                cell.dataset.row = row;
                cell.dataset.col = col;
                
                cell.addEventListener('dragover', dragOver);
                cell.addEventListener('drop', drop);
                
                puzzleContainer.appendChild(cell);
            }
        }
        
        console.log(`Игровое поле создано: ${GRID_COLS}×${GRID_ROWS} клеток`);
    }
    
    // Таймер
    function startTimer() {
        gameState.timer = setInterval(() => {
            gameState.time++;
            const minutes = Math.floor(gameState.time / 60);
            const seconds = gameState.time % 60;
            timeElement.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
        }, 1000);
    }
    
    // Функции перетаскивания
    function dragStart(e) {
        if (!gameState.isPlaying) return;
        gameState.draggedPiece = this;
        e.dataTransfer.setData('text/plain', this.dataset.index);
        
        this.style.opacity = '0.7';
    }
    
    function dragEnd() {
        gameState.draggedPiece = null;
        
        const pieces = document.querySelectorAll('.piece');
        pieces.forEach(piece => {
            piece.style.opacity = '1';
        });
    }
    
    function dragOver(e) {
        e.preventDefault();
    }
    
    function drop(e) {
        e.preventDefault();
        if (!gameState.isPlaying || !gameState.draggedPiece) return;
        
        const piece = gameState.draggedPiece;
        const pieceIndex = parseInt(piece.dataset.index);
        const targetRow = parseInt(this.dataset.row);
        const targetCol = parseInt(this.dataset.col);
        
        if (gameState.puzzleGrid[targetRow][targetCol] !== null) {
            messageElement.textContent = "Эта ячейка уже занята!";
            return;
        }
        
        this.classList.add('filled');
        this.innerHTML = '';
        
        const pieceClone = piece.cloneNode(true);
        pieceClone.draggable = false;
        pieceClone.style.opacity = '1';
        this.appendChild(pieceClone);

        const pieceData = gameState.pieces.find(p => p.index === pieceIndex);
        if (pieceData) {
            pieceData.placed = true;
            pieceData.placedAt = {row: targetRow, col: targetCol};
        }

        gameState.puzzleGrid[targetRow][targetCol] = pieceIndex;
        
        piece.remove();
        
        gameState.moves++;
        movesElement.textContent = gameState.moves;
        
        updateProgress();
        
        checkWin();
    }
    
    // Обновление прогресса
    function updateProgress() {
        const placed = gameState.pieces.filter(p => p.placed).length;
        const progress = Math.round((placed / TOTAL_PIECES) * 100);
        progressElement.textContent = `${progress}%`;
        
        messageElement.textContent = `Собрано: ${placed} из ${TOTAL_PIECES} кусочков`;
    }
    
    function checkWin() {
        const allPlaced = gameState.pieces.every(p => p.placed);
        if (!allPlaced) return;
        
        let correct = 0;
        gameState.pieces.forEach(piece => {
            if (piece.placedAt.row === piece.row && piece.placedAt.col === piece.col) {
                correct++;
            }
        });
        
        if (correct === TOTAL_PIECES) {
            gameState.isPlaying = false;
            clearInterval(gameState.timer);
            
            const minutes = Math.floor(gameState.time / 60);
            const seconds = gameState.time % 60;
            const timeStr = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            
            messageElement.textContent = `🎉 Поздравляем! Вы собрали пазл за ${timeStr} и ${gameState.moves} ходов!`;
            progressElement.textContent = "100%";
            
            document.querySelectorAll('.puzzle-cell.filled').forEach(cell => {
                cell.style.boxShadow = '0 0 15px #ff66a3';
            });
            
            alert(`🎉 Поздравляем!\n\nВы собрали пазл 450×300 пикселей!\nВремя: ${timeStr}\nХоды: ${gameState.moves}\nКусочков: ${TOTAL_PIECES} (9×6)`);
        }
    }
    
    // Вспомогательная функция перемешивания
    function shuffleArray(array) {
        for (let i = array.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [array[i], array[j]] = [array[j], array[i]];
        }
        return array;
    }
    
    resetGame();
});