import './bootstrap';
import Swal from 'sweetalert2';
import { Chart, registerables } from 'chart.js';

// Register Chart.js components
Chart.register(...registerables);

// Make available globally
window.Swal = Swal;
window.Chart = Chart;
