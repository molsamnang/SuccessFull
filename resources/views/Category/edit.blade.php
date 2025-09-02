<!-- Edit Category Modal -->
<div class="modal fade" id="editModal{{ $cat->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $cat->id }}"
    aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content">
            <form action="{{ route($role . '.categories.update', $cat->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel{{ $cat->id }}">Edit Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body row">
                    <!-- Category Name -->
                    <div class="mb-3 col-md-12">
                        <label for="name{{ $cat->id }}" class="form-label">Category Name</label>
                        <input type="text" class="form-control" name="name" id="name{{ $cat->id }}"
                            value="{{ $cat->name }}" required>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-warning">Update Category</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
