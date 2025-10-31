(function(){
    const table = document.getElementById('mulTable');
  
    const thead = document.createElement('thead');
    const headRow = document.createElement('tr');
    headRow.appendChild(document.createElement('th'));
    for (let i = 1; i <= 10; i++) {
      const th = document.createElement('th');
      th.textContent = i;
      headRow.appendChild(th);
    }
    thead.appendChild(headRow);
    table.appendChild(thead);
  
    const tbody = document.createElement('tbody');
    for (let i = 1; i <= 10; i++) {
      const tr = document.createElement('tr');
      const rowHeader = document.createElement('th');
      rowHeader.textContent = i;
      tr.appendChild(rowHeader);
  
      for (let j = 1; j <= 10; j++) {
        const td = document.createElement('td');
        td.textContent = i * j;
        tr.appendChild(td);
      }
  
      tbody.appendChild(tr);
    }
    table.appendChild(tbody);
  })();
  
  