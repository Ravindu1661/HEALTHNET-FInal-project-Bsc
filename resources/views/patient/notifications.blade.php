@include('partials.header')

<div class="notifications-page">
    <div class="container py-5">
        <div class="notifications-header">
            <h2><i class="fas fa-bell me-3"></i>My Notifications</h2>
            <div class="header-actions">
                @if(auth()->user()->notifications()->where('is_read', false)->count() > 0)
                    <button class="btn btn-primary" onclick="markAllAsRead()">
                        <i class="fas fa-check-double me-2"></i>Mark All as Read
                    </button>
                @endif
            </div>
        </div>

        <div class="notifications-filters">
            <button class="filter-btn active" data-filter="all">All</button>
            <button class="filter-btn" data-filter="appointment">Appointments</button>
            <button class="filter-btn" data-filter="payment">Payments</button>
            <button class="filter-btn" data-filter="prescription">Prescriptions</button>
            <button class="filter-btn" data-filter="lab_report">Lab Reports</button>
            <button class="filter-btn" data-filter="reminder">Reminders</button>
        </div>

        <div class="notifications-container">
            @forelse($notifications as $notification)
                <div class="notification-card {{ !$notification->is_read ? 'unread' : '' }}" 
                     data-type="{{ $notification->type }}"
                     data-id="{{ $notification->id }}">
                    <div class="notification-card-icon notification-{{ $notification->type }}">
                        <i class="fas fa-{{ 
                            $notification->type == 'appointment' ? 'calendar-check' : 
                            ($notification->type == 'payment' ? 'credit-card' : 
                            ($notification->type == 'prescription' ? 'pills' : 
                            ($notification->type == 'lab_report' ? 'flask' : 
                            ($notification->type == 'reminder' ? 'clock' : 'bell')))) 
                        }}"></i>
                    </div>
                    <div class="notification-card-content">
                        <div class="notification-card-header">
                            <h5>{{ $notification->title }}</h5>
                            <span class="notification-date">
                                <i class="far fa-clock me-1"></i>{{ $notification->created_at->format('M d, Y h:i A') }}
                            </span>
                        </div>
                        <p>{{ $notification->message }}</p>
                        @if($notification->related_type && $notification->related_id)
                            <a href="#" class="notification-link">View Details <i class="fas fa-arrow-right ms-1"></i></a>
                        @endif
                    </div>
                    <div class="notification-card-actions">
                        @if(!$notification->is_read)
                            <button class="mark-read-btn" onclick="markAsRead({{ $notification->id }})" title="Mark as read">
                                <i class="fas fa-check"></i>
                            </button>
                        @endif
                        <button class="delete-btn" onclick="deleteNotification({{ $notification->id }})" title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            @empty
                <div class="no-notifications-page">
                    <i class="fas fa-bell-slash"></i>
                    <h4>No Notifications Yet</h4>
                    <p>You'll see notifications about appointments, payments, and more here.</p>
                </div>
            @endforelse

            <div class="pagination-wrapper mt-4">
                {{ $notifications->links() }}
            </div>
        </div>
    </div>
</div>

<style>
.notifications-page {
    min-height: 100vh;
    background: #f5f7fa;
    padding-top: 100px;
}

.notifications-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.notifications-header h2 {
    color: #0f4c75;
    font-weight: 700;
    margin: 0;
}

.notifications-filters {
    display: flex;
    gap: 0.8rem;
    margin-bottom: 2rem;
    flex-wrap: wrap;
}

.filter-btn {
    padding: 0.6rem 1.2rem;
    border: 2px solid #e9ecef;
    background: white;
    border-radius: 25px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    color: #666;
}

.filter-btn:hover,
.filter-btn.active {
    background: #0f4c75;
    color: white;
    border-color: #0f4c75;
}

.notification-card {
    background: white;
    border-radius: 15px;
    padding: 1.5rem;
    margin-bottom: 1rem;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    display: flex;
    gap: 1.2rem;
    transition: all 0.3s ease;
    border-left: 4px solid transparent;
}

.notification-card.unread {
    background: #e8f4ff;
    border-left-color: #42a649;
}

.notification-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
}

.notification-card-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.3rem;
    color: white;
    flex-shrink: 0;
}

.notification-appointment {
    background: linear-gradient(135deg, #42a649, #2d7a32);
}

.notification-payment {
    background: linear-gradient(135deg, #f39c12, #e67e22);
}

.notification-prescription {
    background: linear-gradient(135deg, #3498db, #2980b9);
}

.notification-lab_report {
    background: linear-gradient(135deg, #9b59b6, #8e44ad);
}

.notification-reminder {
    background: linear-gradient(135deg, #e74c3c, #c0392b);
}

.notification-general {
    background: linear-gradient(135deg, #95a5a6, #7f8c8d);
}

.notification-card-content {
    flex: 1;
}

.notification-card-header {
    display: flex;
    justify-content: space-between;
    align-items: start;
    margin-bottom: 0.5rem;
}

.notification-card-header h5 {
    color: #0f4c75;
    font-size: 1.1rem;
    font-weight: 600;
    margin: 0;
}

.notification-date {
    font-size: 0.8rem;
    color: #999;
}

.notification-card-content p {
    color: #666;
    margin: 0 0 0.8rem 0;
    line-height: 1.6;
}

.notification-link {
    color: #42a649;
    text-decoration: none;
    font-weight: 500;
    font-size: 0.9rem;
}

.notification-card-actions {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.mark-read-btn,
.delete-btn {
    width: 35px;
    height: 35px;
    border-radius: 8px;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
}

.mark-read-btn {
    background: #e8f5e9;
    color: #42a649;
}

.mark-read-btn:hover {
    background: #42a649;
    color: white;
}

.delete-btn {
    background: #ffebee;
    color: #f44336;
}

.delete-btn:hover {
    background: #f44336;
    color: white;
}

.no-notifications-page {
    text-align: center;
    padding: 4rem 2rem;
    background: white;
    border-radius: 15px;
}

.no-notifications-page i {
    font-size: 4rem;
    color: #ddd;
    margin-bottom: 1rem;
}

.no-notifications-page h4 {
    color: #0f4c75;
    margin-bottom: 0.5rem;
}

@media (max-width: 768px) {
    .notifications-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
    
    .notification-card {
        flex-direction: column;
    }
    
    .notification-card-actions {
        flex-direction: row;
    }
}
</style>

<script>
// Filter notifications
document.querySelectorAll('.filter-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        
        const filter = this.dataset.filter;
        document.querySelectorAll('.notification-card').forEach(card => {
            if (filter === 'all' || card.dataset.type === filter) {
                card.style.display = 'flex';
            } else {
                card.style.display = 'none';
            }
        });
    });
});

// Mark as read function
function markAsRead(id) {
    fetch(`/patient/notifications/${id}/read`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            const card = document.querySelector(`[data-id="${id}"]`);
            if(card) card.classList.remove('unread');
            location.reload();
        }
    });
}

// Mark all as read
function markAllAsRead() {
    fetch('/patient/notifications/mark-all-read', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            location.reload();
        }
    });
}

// Delete notification
function deleteNotification(id) {
    if(!confirm('Are you sure you want to delete this notification?')) return;
    
    fetch(`/patient/notifications/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            const card = document.querySelector(`[data-id="${id}"]`);
            if(card) {
                card.style.animation = 'fadeOut 0.3s ease-out';
                setTimeout(() => card.remove(), 300);
            }
        }
    });
}
</script>

@include('partials.footer')
