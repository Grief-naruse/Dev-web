/**
 * Application Ticketing - Script Principal
 * Gère : Mode Sombre (Instantané), SweetAlert2, et Chart.js
 */

document.addEventListener('DOMContentLoaded', () => {

    /* ==========================================================================
       1. GESTION DU MODE SOMBRE (DARK MODE)
       ========================================================================== */
    const darkModeToggle = document.getElementById('darkModeToggle');
    const root = document.documentElement; // On cible la racine (<html>)

    // Synchronisation de l'interrupteur avec le thème stocké
    if (localStorage.getItem('theme') === 'dark') {
        if (darkModeToggle) darkModeToggle.checked = true;
        // La classe est déjà ajoutée par le script du <head>, on s'assure juste de la cohérence
        root.classList.add('dark-mode');
    }

    if (darkModeToggle) {
        darkModeToggle.addEventListener('change', () => {
            if (darkModeToggle.checked) {
                root.classList.add('dark-mode');
                localStorage.setItem('theme', 'dark');
            } else {
                root.classList.remove('dark-mode');
                localStorage.setItem('theme', 'light');
            }
        });
    }


    /* ==========================================================================
       2. NOTIFICATIONS (SWEETALERT2 TOASTS)
       ========================================================================== */
    const successMessage = document.querySelector('.alert-success');
    
    if (successMessage && window.Swal) {
        Swal.fire({
            icon: 'success',
            title: 'Opération réussie',
            text: successMessage.innerText,
            timer: 3000,
            showConfirmButton: false,
            toast: true,
            position: 'top-end',
            timerProgressBar: true,
        });
        successMessage.remove();
    }


    /* ==========================================================================
       3. CONFIRMATION DE SUPPRESSION (SWEETALERT2)
       ========================================================================== */
    document.addEventListener('click', (e) => {
        const deleteBtn = e.target.closest('button[onclick*="confirm"]');
        
        if (deleteBtn && window.Swal) {
            e.preventDefault();
            const form = deleteBtn.closest('form');

            Swal.fire({
                title: 'Es-tu sûr ?',
                text: "Cette action est irréversible !",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e74c3c',
                cancelButtonColor: '#7f8c8d',
                confirmButtonText: 'Oui, supprimer !',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }
    });


    /* ==========================================================================
       4. GRAPHIQUE DU DASHBOARD (CHART.JS)
       ========================================================================== */
    const chartCanvas = document.getElementById('myChart');
    
    if (chartCanvas && window.Chart) {
        new Chart(chartCanvas, {
            type: 'bar',
            data: {
                labels: ['Projets', 'Tickets', 'Heures'],
                datasets: [{
                    label: 'Statistiques',
                    data: [2, 5, 14.5],
                    backgroundColor: ['#3498db', '#e74c3c', '#27ae60'],
                    borderRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { grid: { color: 'rgba(200, 200, 200, 0.1)' } },
                    x: { grid: { display: false } }
                }
            }
        });
    }

    console.log('🚀 JS Intégral opérationnel');
});