// Script yoinked from http://www.mredkj.com/tutorials/tableaddrow.html
function addRowToTable()
{
  var tbl = document.getElementById('form_table');
  var lastRow = tbl.rows.length;
  // if there's no header row in the table, then iteration = lastRow + 1
  var iteration = lastRow;
  var row = tbl.insertRow(lastRow);
  
  // id cell
  var cellID = row.insertCell(0);
  var textNode = document.createTextNode(iteration);
  cellID.appendChild(textNode);
  
  // vorname vk cell label
  var cellVornameVKLabel = row.insertCell(1);
  var el = document.createElement('label');
  el.for = 'vk_vorname_einsatz' + iteration;
  cellVornameVKLabel.innerHTML = 'Vorname VK';
  cellVornameVKLabel.appendChild(el);

  // vorname vk cell
  var cellVornameVK = row.insertCell(2);
  var el = document.createElement('input');
  el.type = 'text';
  el.name = 'vk_vorname_einsatz' + iteration;
  el.id = 'vk_vorname_einsatz' + iteration;
  el.maxLength = '50';
  el.required = true;

  cellVornameVK.appendChild(el);

  // nachname vk cell label
  var cellNachnameVKLabel = row.insertCell(3);
  var el = document.createElement('label');
  el.for = 'vk_nachname_einsatz' + iteration;
  cellNachnameVKLabel.innerHTML = 'Nachname VK';
  cellNachnameVKLabel.appendChild(el);

  // nachname vk cell
  var cellNachnameVK = row.insertCell(4);
  var el = document.createElement('input');
  el.type = 'text';
  el.name = 'vk_nachname_einsatz' + iteration;
  el.id = 'vk_nachname_einsatz' + iteration;
  el.maxLength = '50';
  el.required = true;

  cellNachnameVK.appendChild(el);

  // einsastzstunden vk cell label
  var cellEInsatzstundenVKLabel = row.insertCell(5);
  var el = document.createElement('label');
  el.for = 'vk_einsatzstunden_einsatz' + iteration;
  cellEInsatzstundenVKLabel.innerHTML = 'Einsatzstunden VK';
  cellEInsatzstundenVKLabel.appendChild(el);

  // einsastzstunden vk cell
  var cellEInsatzstundenVK = row.insertCell(6);
  var el = document.createElement('input');
  el.type = 'number';
  el.name = 'vk_einsatzstunden_einsatz' + iteration;
  el.id = 'vk_einsatzstunden_einsatz' + iteration;
  el.min = '1';
  el.max = '12';
  el.required = true;

  cellEInsatzstundenVK.appendChild(el);
}

function removeRowFromTable()
{
  var tbl = document.getElementById('form_table');
  var lastRow = tbl.rows.length;
  if (lastRow > 2) tbl.deleteRow(lastRow - 1);
}