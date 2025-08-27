// Admin Jadwal Scripts
document.addEventListener("DOMContentLoaded", function () {
    const mapelSelect = document.getElementById("mapel_id");
    const guruSelect = document.getElementById("guru_id");

    if (mapelSelect && guruSelect) {
        mapelSelect.addEventListener("change", function () {
            const selectedOption =
                mapelSelect.options[mapelSelect.selectedIndex];
            const guruId = selectedOption.dataset.guruId;

            if (!guruId) {
                guruSelect.value = "";
            } else {
                guruSelect.value = guruId;
            }
        });

        if (mapelSelect.value) {
            mapelSelect.dispatchEvent(new Event("change"));
        }
    }
});
