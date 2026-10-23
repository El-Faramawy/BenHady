@props(['responsive' => false])

<div class="card-body">
    <div class="{{$responsive ? 'table-responsive' : ''}}">
        <table id="exportexample" class="table table-striped table-responsive-lg card-table table-vcenter text-nowrap mb-0 align-items-center mb-0 modern-admin-table">
            <thead class="admin-table-header text-white">
                <tr>
                    {{$slot}}
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>

    </div>
</div>
