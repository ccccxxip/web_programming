const ROWS = 12;
const COLS = 12;
const MINES = 20;

let board = [];
let firstClickDone = false;
let gameOver = false;
let cellsOpened = 0;
let cursorRow = 0;
let cursorCol = 0;
let flagsPlaced = 0;

// DOM
const boardEl = document.getElementById('board');
const mineCountEl = document.getElementById('mine-count');
const flagCountEl = document.getElementById('flag-count');
const restartBtn = document.getElementById('restart-btn');

mineCountEl.textContent = MINES;
restartBtn.addEventListener('click', initGame);
boardEl.addEventListener('contextmenu', e => e.preventDefault());
boardEl.tabIndex = 0;
boardEl.addEventListener('keydown', handleKey);

initGame();

function initGame() {
  board = [];
  boardEl.innerHTML = '';
  firstClickDone = false;
  gameOver = false;
  cellsOpened = 0;
  flagsPlaced = 0;
  cursorRow = 0;
  cursorCol = 0;
  flagCountEl.textContent = flagsPlaced;
  mineCountEl.textContent = MINES;

  for (let r = 0; r < ROWS; r++) {
    const row = [];
    for (let c = 0; c < COLS; c++) {
      const cell = {
        row: r,
        col: c,
        mine: false,
        open: false,
        flag: false,
        number: 0,
        el: null
      };
      row.push(cell);
    }
    board.push(row);
  }

  for (let r = 0; r < ROWS; r++) {
    for (let c = 0; c < COLS; c++) {
      const div = document.createElement('div');
      div.className = 'cell hidden';
      div.dataset.row = r;
      div.dataset.col = c;

      div.addEventListener('click', onLeftClick);
      div.addEventListener('mousedown', e => {
        if (e.button === 2) {
          e.preventDefault();
          onRightClick(e);
        }
      });

      board[r][c].el = div;
      boardEl.appendChild(div);
    }
  }

  updateCursorHighlight();
}

function placeMines(excludeRow, excludeCol) {
  let placed = 0;
  while (placed < MINES) {
    const r = Math.floor(Math.random() * ROWS);
    const c = Math.floor(Math.random() * COLS);

    if (r === excludeRow && c === excludeCol) continue;
    if (board[r][c].mine) continue;

    board[r][c].mine = true;
    placed++;
  }

  for (let r = 0; r < ROWS; r++) {
    for (let c = 0; c < COLS; c++) {
      if (board[r][c].mine) continue;
      board[r][c].number = countAdjacentMines(r, c);
    }
  }
}

function countAdjacentMines(row, col) {
  let count = 0;
  for (let dr = -1; dr <= 1; dr++) {
    for (let dc = -1; dc <= 1; dc++) {
      if (dr === 0 && dc === 0) continue;
      const nr = row + dr;
      const nc = col + dc;
      if (inBounds(nr, nc) && board[nr][nc].mine) count++;
    }
  }
  return count;
}

function inBounds(r, c) {
  return r >= 0 && r < ROWS && c >= 0 && c < COLS;
}

function onLeftClick(e) {
  const cell = getCellFromEvent(e);
  if (!cell || gameOver) return;

  if (!firstClickDone) {
    placeMines(cell.row, cell.col);
    firstClickDone = true;
  }

  openCell(cell.row, cell.col);
}

function onRightClick(e) {
  const cell = getCellFromEvent(e);
  if (!cell || gameOver) return;
  toggleFlag(cell.row, cell.col);
}

function getCellFromEvent(e) {
  const r = parseInt(e.target.dataset.row, 10);
  const c = parseInt(e.target.dataset.col, 10);
  if (!inBounds(r, c)) return null;
  return board[r][c];
}

function openCell(row, col) {
  if (!inBounds(row, col)) return;
  const cell = board[row][col];
  if (cell.open || cell.flag) return;

  cell.open = true;
  cell.el.classList.remove('hidden');
  cell.el.classList.add('open');
  cell.el.classList.remove('flag');
  cell.el.textContent = '';
  cellsOpened++;

  if (cell.mine) {
    cell.el.classList.add('mine');
    cell.el.textContent = '💣';
    endGame(false);
    return;
  }

  if (cell.number > 0) {
    cell.el.textContent = cell.number;
    cell.el.classList.add('num-' + cell.number);
  } else {
    for (let dr = -1; dr <= 1; dr++) {
      for (let dc = -1; dc <= 1; dc++) {
        if (dr === 0 && dc === 0) continue;
        const nr = row + dr;
        const nc = col + dc;
        if (inBounds(nr, nc) && 
            !board[nr][nc].open && 
            !board[nr][nc].flag && 
            !board[nr][nc].mine) {
          openCell(nr, nc);
        }
      }
    }
  }

  checkWin();
}

function toggleFlag(row, col) {
  const cell = board[row][col];
  if (cell.open) return;

  cell.flag = !cell.flag;
  if (cell.flag) {
    cell.el.classList.add('flag');
    cell.el.textContent = '⚑';
    flagsPlaced++;
  } else {
    cell.el.classList.remove('flag');
    cell.el.textContent = '';
    flagsPlaced--;
  }
  flagCountEl.textContent = flagsPlaced;
  mineCountEl.textContent = MINES - flagsPlaced;
}

function endGame(won) {
  gameOver = true;

  for (let r = 0; r < ROWS; r++) {
    for (let c = 0; c < COLS; c++) {
      const cell = board[r][c];
      cell.el.classList.add('disabled');
      if (cell.mine && !cell.open) {
        cell.el.classList.remove('hidden');
        cell.el.classList.add('open', 'mine');
        cell.el.textContent = '💣';
      }
    }
  }

  setTimeout(() => {
    alert(won ? 'Вы выиграли!' : 'Вы подорвались на мине!');
  }, 50);
}

function checkWin() {
  const totalCells = ROWS * COLS;
  const safeCells = totalCells - MINES;
  if (cellsOpened >= safeCells && !gameOver) {
    endGame(true);
  }
}

function handleKey(e) {
  if (gameOver) return;

  switch (e.key) {
    case 'ArrowUp':
      if (cursorRow > 0) cursorRow--;
      e.preventDefault();
      break;
    case 'ArrowDown':
      if (cursorRow < ROWS - 1) cursorRow++;
      e.preventDefault();
      break;
    case 'ArrowLeft':
      if (cursorCol > 0) cursorCol--;
      e.preventDefault();
      break;
    case 'ArrowRight':
      if (cursorCol < COLS - 1) cursorCol++;
      e.preventDefault();
      break;
    case ' ':
    case 'Enter':
      e.preventDefault();
      if (e.ctrlKey) {
        toggleFlag(cursorRow, cursorCol);
      } else {
        if (!firstClickDone) {
          placeMines(cursorRow, cursorCol);
          firstClickDone = true;
        }
        openCell(cursorRow, cursorCol);
      }
      break;
    default:
      return;
  }

  updateCursorHighlight();
}

function updateCursorHighlight() {
  for (let r = 0; r < ROWS; r++) {
    for (let c = 0; c < COLS; c++) {
      board[r][c].el.classList.remove('cursor');
    }
  }
  board[cursorRow][cursorCol].el.classList.add('cursor');
}
