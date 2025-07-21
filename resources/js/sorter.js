document.addEventListener("DOMContentLoaded", function () {
    const table = document.getElementById("sortableTable");
    if (!table) {
        return;
    }
    const buttons = table.querySelectorAll(".sort-btn");
    let sortDirection = {};

    buttons.forEach((button) => {
        button.addEventListener("click", () => {
            const columnIndex = parseInt(button.dataset.column);
            const rows = Array.from(table.tBodies[0].rows);

            const direction =
                sortDirection[columnIndex] === "asc" ? "desc" : "asc";
            sortDirection[columnIndex] = direction;

            rows.sort((a, b) => {
                const cellA = a.cells[columnIndex].textContent
                    .trim()
                    .toLowerCase();
                const cellB = b.cells[columnIndex].textContent
                    .trim()
                    .toLowerCase();

                if (!isNaN(cellA) && !isNaN(cellB)) {
                    return direction === "asc" ? cellA - cellB : cellB - cellA;
                }

                return direction === "asc"
                    ? cellA.localeCompare(cellB)
                    : cellB.localeCompare(cellA);
            });

            rows.forEach((row) => table.tBodies[0].appendChild(row));
        });
    });
});
