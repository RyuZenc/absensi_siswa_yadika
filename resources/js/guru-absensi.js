// Guru Absensi Scripts
document.addEventListener("DOMContentLoaded", function () {
    try {
        // Countdown Timer
        const kodeContainer = document.getElementById("kode-aktif-container");
        const formContainer = document.getElementById(
            "form-buat-kode-container"
        );
        const countdownElement = document.getElementById("countdown-timer");

        if (
            kodeContainer &&
            countdownElement &&
            kodeContainer.style.display !== "none"
        ) {
            const waktuBerlaku = new Date(
                kodeContainer.dataset.waktuBerlaku
            ).getTime();

            const countdownInterval = setInterval(function () {
                try {
                    const sekarang = new Date().getTime();
                    const sisaWaktu = waktuBerlaku - sekarang;

                    if (sisaWaktu > 0) {
                        const menit = Math.floor(
                            (sisaWaktu % (1000 * 60 * 60)) / (1000 * 60)
                        );
                        const detik = Math.floor(
                            (sisaWaktu % (1000 * 60)) / 1000
                        );
                        countdownElement.textContent = `Sisa Waktu: ${String(
                            menit
                        ).padStart(2, "0")}:${String(detik).padStart(2, "0")}`;
                    } else {
                        clearInterval(countdownInterval);
                        countdownElement.textContent = "Waktu habis!";
                        if (kodeContainer) kodeContainer.style.display = "none";
                        if (formContainer)
                            formContainer.style.display = "block";
                    }
                } catch (error) {
                    console.error("Error in countdown:", error);
                    clearInterval(countdownInterval);
                }
            }, 1000);
        }
    } catch (error) {
        console.error("Error initializing countdown:", error);
    }

    // Auto-save absensi
    try {
        const radios = document.querySelectorAll(".absensi-radio");
        const csrfToken = document
            .querySelector('meta[name="csrf-token"]')
            ?.getAttribute("content");
        const updateUrl = window.guruAbsensiConfig?.updateUrl;
        const sesiAbsenId = window.guruAbsensiConfig?.sesiAbsenId;

        radios.forEach(function (radio) {
            radio.addEventListener("change", function () {
                try {
                    const siswaId = this.dataset.siswaId;
                    const status = this.dataset.status;

                    if (!siswaId || !status) {
                        console.error("Missing siswa ID or status");
                        return;
                    }

                    fetch(updateUrl, {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": csrfToken,
                        },
                        body: JSON.stringify({
                            siswa_id: siswaId,
                            status: status,
                            sesi_absen_id: sesiAbsenId,
                        }),
                    })
                        .then((response) => {
                            if (!response.ok) {
                                throw new Error(
                                    `HTTP error! status: ${response.status}`
                                );
                            }
                            return response.json();
                        })
                        .then((data) => {
                            console.log("Sukses update absensi:", data);
                        })
                        .catch((error) => {
                            console.error("Gagal update absensi:", error);
                            // Revert radio button if error
                            this.checked = false;
                        });
                } catch (error) {
                    console.error("Error in radio change handler:", error);
                }
            });
        });
    } catch (error) {
        console.error("Error setting up radio handlers:", error);
    }

    // Hadirkan semua button
    try {
        const hadirkanSemuaBtn = document.getElementById("hadirkan-semua-btn");
        if (hadirkanSemuaBtn) {
            hadirkanSemuaBtn.addEventListener("click", () => {
                window.dispatchEvent(
                    new CustomEvent("open-modal", {
                        detail: "hadirkan-semua",
                    })
                );
            });
        }
    } catch (error) {
        console.error("Error setting up hadirkan semua button:", error);
    }

    // Modal confirmed handler
    window.addEventListener("confirmed", (e) => {
        try {
            if (e.detail.modal === "hadirkan-semua") {
                const semuaRadioHadir = document.querySelectorAll(
                    'input[type="radio"][value="hadir"]'
                );
                const fetchPromises = [];
                const csrfToken = document
                    .querySelector('meta[name="csrf-token"]')
                    ?.getAttribute("content");
                const updateUrl = window.guruAbsensiConfig?.updateUrl;
                const sesiAbsenId = window.guruAbsensiConfig?.sesiAbsenId;

                semuaRadioHadir.forEach((radio) => {
                    if (!radio.checked) {
                        radio.checked = true;

                        const siswaId = radio.dataset.siswaId;
                        const status = radio.value;

                        if (siswaId && status) {
                            const promise = fetch(updateUrl, {
                                method: "POST",
                                headers: {
                                    "Content-Type": "application/json",
                                    "X-CSRF-TOKEN": csrfToken,
                                },
                                body: JSON.stringify({
                                    siswa_id: siswaId,
                                    status: status,
                                    sesi_absen_id: sesiAbsenId,
                                }),
                            });

                            fetchPromises.push(promise);
                        }
                    }
                });

                Promise.all(fetchPromises)
                    .then(() => {
                        console.log("Semua absensi berhasil diperbarui");
                        location.reload();
                    })
                    .catch((error) => {
                        console.error("Gagal memperbarui absensi:", error);
                        alert("Terjadi kesalahan saat memperbarui absensi");
                    });
            }
        } catch (error) {
            console.error("Error in confirmed handler:", error);
        }
    });
});
