<div class="modal fade" id="showModal{{ $customer->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">Customer Info</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p><strong>Full Name:</strong> {{ $customer->full_name }}</p>
                <p><strong>Gender:</strong> {{ $customer->gender }}</p>
                <p><strong>Phone:</strong> {{ $customer->phone }}</p>
                <p><strong>Address:</strong> {{ $customer->address }}</p>
                <p><strong>Created At:</strong> {{ $customer->created_at->format('d-m-Y H:i') }}</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
