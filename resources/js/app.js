import "./bootstrap";
import "./sorter";
import "./clock";
import "./sorter-jadwal";

import Alpine from "alpinejs";
import collapse from "@alpinejs/collapse";

Alpine.plugin(collapse);
window.Alpine = Alpine;

Alpine.start();
