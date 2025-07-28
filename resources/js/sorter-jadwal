document.addEventListener("DOMContentLoaded", function () {
    const table = document.getElementById("sortableTableJadwal");
    if (!table) {
        return;
    }

    const buttons = table.querySelectorAll(".sort-btn-jadwal");
    let sortDirection = {};

    buttons.forEach((button) => {
        button.addEventListener("click", () => {
            const columnIndex = parseInt(button.dataset.column);
            const direction =
                sortDirection[columnIndex] === "asc" ? "desc" : "asc";
            sortDirection[columnIndex] = direction;

            table.querySelectorAll("tbody.group-section").forEach((tbody) => {
                const rows = Array.from(tbody.querySelectorAll("tr.data-row"));

                rows.sort((a, b) => {
                    const cellA = a.cells[columnIndex].textContent
                        .trim()
                        .toLowerCase();
                    const cellB = b.cells[columnIndex].textContent
                        .trim()
                        .toLowerCase();

                    if (!isNaN(cellA) && !isNaN(cellB)) {
                        return direction === "asc"
                            ? cellA - cellB
                            : cellB - cellA;
                    }

                    return direction === "asc"
                        ? cellA.localeCompare(cellB)
                        : cellB.localeCompare(cellA);
                });

                rows.forEach((row) => tbody.appendChild(row));
            });
        });
    });
});
