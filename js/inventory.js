{
  function sortTable(columnIndex) {
    var table = document.getElementById("inventory");
    var tbody = table.querySelector("tbody");
    var rows = Array.from(tbody.rows);

    rows.sort(function (a, b) {
      var cellA = a.cells[columnIndex].innerText.toLowerCase();
      var cellB = b.cells[columnIndex].innerText.toLowerCase();

      return cellA.localeCompare(cellB);
    });

    // Create a new table body to hold the sorted rows
    var newTbody = document.createElement("tbody");
    rows.forEach(function (row) {
      newTbody.appendChild(row);
    });

    // Replace the existing table body with the sorted one
    table.replaceChild(newTbody, tbody);
  }


  // Delete an item from the table
  function deleteItem(row) {
    row.remove();
  }

  document.getElementById("addBtn").addEventListener("click", function() {
    var itemForm = document.getElementById("addItem");
    if (itemForm.classList.contains("show")) {
      itemForm.classList.remove("show");
    } else {
      itemForm.classList.add("show");
    }
  });
}
