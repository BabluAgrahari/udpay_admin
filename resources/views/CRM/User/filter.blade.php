<div class="row mb-2" id="filter" <?= empty($filter) ? "style='display:none'" : '' ?>>

    <div class="col-md-12 ml-auto">

        <form action="{{ url('crm/loan') }}">

            <div class="row">



                <div class="col-md-3 mb-3">

                    <label>Name</label>

                    <input type="text" class="form-control form-control-sm" name="employee_name"
                        value="{{ !empty($filter['employee_name']) ? $filter['employee_name'] : '' }}"
                        placeholder="Enter Employee Name">

                </div>

                <div class="col-md-2 mb-3">

                    <label>Status</label>

                    <select class="form-select form-select-sm" name="status">

                        <option value="">All</option>

                        <option value="pending" @selected(!empty($filter['status']) && $filter['status'] == 'pending')>
                            Pending</option>

                        <option value="on_going" @selected(!empty($filter['status']) && $filter['status'] == 'on_going')>
                            On Going</option>

                        <option value="completed" @selected(!empty($filter['status']) && $filter['status'] == 'completed')>Completed</option>

                    </select>

                </div>

                <div class="col-md-3 mt-4">

                    <button type="submit" class="btn btn-outline-primary btn-sm"><i
                            class="fas fa-search"></i>&nbsp;Search</button>

                    <a href="{{ url('crm/loan') }}" class="btn btn-outline-danger btn-sm"><i
                            class="fas fa-eraser"></i>&nbsp;Clear</a>

                </div>

            </div>

        </form>

    </div>

</div>