<form action="{{ url('crm/categories') }}" method="GET" class="mb-4">
    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                <label for="search">Search</label>
                <input type="text" 
                       name="search" 
                       id="search" 
                       class="form-control" 
                       value="{{ request('search') }}" 
                       placeholder="Search by name or description">
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="form-group">
                <label for="status">Status</label>
                <select name="status" id="status" class="form-control">
                    <option value="">All</option>
                    <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="form-group">
                <label for="parent_id">Parent Category</label>
                <select name="parent_id" id="parent_id" class="form-control">
                    <option value="">All</option>
                    @foreach($parentCategories as $parent)
                        <option value="{{ $parent->_id }}" 
                                {{ request('parent_id') == $parent->_id ? 'selected' : '' }}>
                            {{ $parent->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="form-group">
                <label>&nbsp;</label>
                <div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Filter
                    </button>
                    <a href="{{ url('crm/categories') }}" class="btn btn-secondary">
                        <i class="fas fa-redo"></i> Reset
                    </a>
                </div>
            </div>
        </div>
    </div>
</form> 