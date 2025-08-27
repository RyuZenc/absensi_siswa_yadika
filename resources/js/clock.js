function updateClock() {
    try {
        const now = new Date();
        const days = [
            "Minggu",
            "Senin",
            "Selasa",
            "Rabu",
            "Kamis",
            "Jumat",
            "Sabtu",
        ];
        const months = [
            "Januari",
            "Februari",
            "Maret",
            "April",
            "Mei",
            "Juni",
            "Juli",
            "Agustus",
            "September",
            "Oktober",
            "November",
            "Desember",
        ];

        const day = days[now.getDay()];
        const date = now.getDate();
        const month = months[now.getMonth()];
        const year = now.getFullYear();
        const hours = String(now.getHours()).padStart(2, "0");
        const minutes = String(now.getMinutes()).padStart(2, "0");
        const seconds = String(now.getSeconds()).padStart(2, "0");

        const clockString = `${day}, ${date} ${month} ${year} | ${hours}:${minutes}:${seconds}`;
        const clockElement = document.getElementById("realtime-clock");

        if (clockElement) {
            clockElement.textContent = clockString;
        }
    } catch (error) {
        console.error("Error updating clock:", error);
    }
}

document.addEventListener("DOMContentLoaded", function () {
    updateClock();
    setInterval(updateClock, 1000);
});
