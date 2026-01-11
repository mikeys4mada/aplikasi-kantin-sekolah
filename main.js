function showMenu(menu) {
    // Hide all cards
    document.getElementById('pembelian').style.display = 'none';
    document.getElementById('penjualan').style.display = 'none';
    document.getElementById('laporan').style.display = 'none';

    // Show selected menu
    document.getElementById(menu).style.display = 'block';
}

// Format input as Rupiah when user types
function formatRupiahInput(element) {
    // Get the input value and remove non-digit characters
    let value = element.value.replace(/\D/g, '');
    
    // Convert to number and format as Rupiah
    const numberValue = parseInt(value || 0);
    const formattedValue = new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    }).format(numberValue).replace('Rp', '').trim();
    
    // Update the input value
    element.value = formattedValue;
    
    // Store the raw numeric value in a data attribute
    element.setAttribute('data-raw-value', numberValue);
    
    return numberValue;
}

// Parse Rupiah value to number
function parseRupiah(value) {
    if (!value) return 0;
    // If it's an input element with data-raw-value
    if (value.nodeType === 1) {
        return parseInt(value.getAttribute('data-raw-value') || 0);
    }
    // If it's a string value
    return parseInt(value.toString().replace(/\D/g, '') || 0);
}

// Format number as Rupiah
function formatRupiah(amount) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    }).format(amount).replace('Rp', '').trim();
}

// Calculate the total payment with percentage discount
function hitung() {
    const total = parseRupiah(document.getElementById('total'));
    const ppn = Math.round(total * 0.1); // 10% PPN
    
    // Get discount percentage and calculate discount amount
    const diskonPersen = parseFloat(document.getElementById('diskon').value) || 0;
    const diskonAmount = Math.round(total * (diskonPersen / 100));
    
    const totalBayar = total + ppn - diskonAmount;
    const bayar = parseRupiah(document.getElementById('bayar'));
    const kembali = bayar - totalBayar;

    // Format the output values
    document.getElementById('ppn').value = formatRupiah(ppn);
    document.getElementById('totalBayar').value = formatRupiah(totalBayar);
    document.getElementById('kembali').value = formatRupiah(kembali);
    
    // Update the discount amount display
    document.getElementById('diskon-amount').textContent = formatRupiah(diskonAmount);
}

// Add event listeners for input formatting
function initRupiahInputs() {
    // Format Rupiah inputs
    const rupiahInputs = ['total', 'bayar'];
    rupiahInputs.forEach(id => {
        const input = document.getElementById(id);
        if (input) {
            // Format on input
            input.addEventListener('input', function() {
                formatRupiahInput(this);
                hitung(); // Recalculate on input change
            });
            
            // Format on blur (when leaving the field)
            input.addEventListener('blur', function() {
                formatRupiahInput(this);
            });
        }
    });
    
    // Handle percentage input for discount
    const diskonInput = document.getElementById('diskon');
    if (diskonInput) {
        diskonInput.addEventListener('input', function() {
            // Only allow numbers and decimal point
            this.value = this.value.replace(/[^0-9.]/g, '');
            // Ensure only one decimal point
            if ((this.value.match(/\./g) || []).length > 1) {
                this.value = this.value.slice(0, -1);
            }
            // Limit to 2 decimal places
            if (this.value.includes('.')) {
                const parts = this.value.split('.');
                if (parts[1].length > 2) {
                    this.value = parts[0] + '.' + parts[1].substring(0, 2);
                }
            }
            hitung(); // Recalculate on input change
        });
    }
}

// Initialize when the document is loaded
document.addEventListener('DOMContentLoaded', function() {
    initRupiahInputs();
    hitung(); // Initial calculation
});

// Show pembelian by default
showMenu('pembelian');
