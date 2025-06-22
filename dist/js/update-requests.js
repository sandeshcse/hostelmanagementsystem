// Update modal HTML structure
const modalHtml = `
<div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateModalLabel">Update Request</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="updateForm">
                    <input type="hidden" id="requestId" name="request_id">
                    <input type="hidden" id="requestType" name="request_type">
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="pending">Pending</option>
                            <option value="approved">Approved</option>
                            <option value="rejected">Rejected</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="remarks">Admin Remarks</label>
                        <textarea class="form-control" id="remarks" name="remarks" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="submitUpdate">Update</button>
            </div>
        </div>
    </div>
</div>
`;

// Add modal to document body
document.body.insertAdjacentHTML('beforeend', modalHtml);

// Handle complaint updates
document.querySelectorAll('.update-complaint').forEach(button => {
    button.addEventListener('click', function() {
        const id = this.dataset.id;
        const status = this.dataset.status;
        const remarks = this.dataset.remarks || '';

        document.getElementById('requestId').value = id;
        document.getElementById('requestType').value = 'complaint';
        document.getElementById('status').value = status;
        document.getElementById('remarks').value = remarks;

        $('#updateModal').modal('show');
    });
});

// Handle leave request updates
document.querySelectorAll('.update-leave').forEach(button => {
    button.addEventListener('click', function() {
        const id = this.dataset.id;
        const status = this.dataset.status;
        const remarks = this.dataset.remarks || '';

        document.getElementById('requestId').value = id;
        document.getElementById('requestType').value = 'leave';
        document.getElementById('status').value = status;
        document.getElementById('remarks').value = remarks;

        $('#updateModal').modal('show');
    });
});

// Handle form submission
document.getElementById('submitUpdate').addEventListener('click', function() {
    const form = document.getElementById('updateForm');
    const requestType = document.getElementById('requestType').value;
    const requestId = document.getElementById('requestId').value;
    const status = document.getElementById('status').value;
    const remarks = document.getElementById('remarks').value;
    
    const data = {
        [requestType === 'complaint' ? 'complaint_id' : 'leave_id']: requestId,
        status: status,
        remarks: remarks,
        update: true
    };

    const url = requestType === 'complaint' ? 'manage-complaints.php' : 'manage-leave-requests.php';

    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: new URLSearchParams(data).toString()
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            alert(data.message);
            $('#updateModal').modal('hide');
            window.location.reload();
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
    });
});