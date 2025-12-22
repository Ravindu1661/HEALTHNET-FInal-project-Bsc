// Sidebar Toggle
document.getElementById('sidebarToggle').addEventListener('click', function() {
    document.getElementById('sidebar').classList.toggle('active');
});

// Notification Panel Toggle
document.getElementById('notificationIcon').addEventListener('click', function() {
    document.getElementById('notificationPanel').classList.toggle('active');
});

document.getElementById('closeNotifications').addEventListener('click', function() {
    document.getElementById('notificationPanel').classList.remove('active');
});

// Order Statistics Chart
const orderCtx = document.getElementById('orderChart').getContext('2d');
const orderChart = new Chart(orderCtx, {
    type: 'doughnut',
    data: {
        labels: ['Direct', 'Social', 'Referral', 'Marketing'],
        datasets: [{
            data: [45.8, 18.2, 22.6, 13.4],
            backgroundColor: ['#4285F4', '#34A853', '#FBBC05', '#EA4335'],
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                display: false
            }
        },
        cutout: '70%'
    }
});

// Sales Statistics Chart
const salesCtx = document.getElementById('salesChart').getContext('2d');
const salesChart = new Chart(salesCtx, {
    type: 'bar',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        datasets: [{
            label: 'Sales',
            data: [450, 380, 520, 410, 480, 530, 420, 490, 510, 470, 500, 540],
            backgroundColor: '#4285F4',
            borderRadius: 5,
            barThickness: 15
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: {
                    display: true,
                    color: '#f0f0f0'
                }
            },
            x: {
                grid: {
                    display: false
                }
            }
        }
    }
});

// Revenue Chart
const revenueCtx = document.getElementById('revenueChart').getContext('2d');
const revenueChart = new Chart(revenueCtx, {
    type: 'line',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        datasets: [
            {
                label: 'Total Expenses',
                data: [35, 42, 38, 45, 40, 48, 43, 50, 47, 52, 49, 55],
                borderColor: '#4285F4',
                backgroundColor: 'rgba(66, 133, 244, 0.1)',
                tension: 0.4,
                fill: true
            },
            {
                label: 'Total Earnings',
                data: [50, 58, 55, 62, 59, 68, 63, 72, 69, 75, 71, 80],
                borderColor: '#34A853',
                backgroundColor: 'rgba(52, 168, 83, 0.1)',
                tension: 0.4,
                fill: true
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: {
                    display: true,
                    color: '#f0f0f0'
                }
            },
            x: {
                grid: {
                    display: false
                }
            }
        }
    }
});

// Approval Functions
function approveRequest(id, type) {
    if (confirm('Are you sure you want to approve this request?')) {
        fetch(`/admin/${type}s/${id}/approve`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Request approved successfully');
                location.reload();
            }
        });
    }
}

function rejectRequest(id, type) {
    const reason = prompt('Please enter rejection reason:');
    if (reason) {
        fetch(`/admin/${type}s/${id}/reject`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ reason: reason })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Request rejected');
                location.reload();
            }
        });
    }
}

function viewDetails(id, type) {
    window.location.href = `/admin/${type}s/${id}`;
}

// Mark notification as read
document.querySelectorAll('.notification-item.unread').forEach(item => {
    item.addEventListener('click', function() {
        this.classList.remove('unread');
        // Send AJAX request to mark as read
    });
});

// Auto-refresh stats every 30 seconds
setInterval(() => {
    fetch('/admin/dashboard/stats')
        .then(response => response.json())
        .then(data => {
            // Update stats dynamically
            document.querySelector('.stat-card-primary h3').textContent = data.total_users;
            document.querySelector('.stat-card-success h3').textContent = data.total_doctors;
            // ... update other stats
        });
}, 30000);
