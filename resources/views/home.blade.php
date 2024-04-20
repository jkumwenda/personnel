@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>{{ session('success') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close" onclick="$('.alert').alert('close')">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if(Auth::user()->hasRole('personnel'))
        @if(Auth::user()->application()->first() == null)
            <div class="row">
                <div class="col-12">
                    <div class="card elevation-0 border-0">
                        <h3 class="card-header"><b>Welcome {{ Auth::user()->name }}!</b></h3>
                        <div class="card-body">
                            <p class="card-text">You have not applied for any license yet. Click the button below to apply for a license.</p>
                            <a href="{{ route('applications.apply') }}" class="btn btn-primary">Apply for licence</a>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="row">
                <div class="col-md-6">
                    <h4><b>{{ auth()->user()->first_name }}</b></h4>
                    <p class="text-muted">We wish you all the best as you are preparing for your <b>{{ $exam->exam_name }} Personnel Registration Exam.</b> More information is down below the Calendar</p>
                    <h5>Your Examination number is <b>{{$exam_number->exam_number}}</b></h5>
                    <img src="{{asset('dist/img/8262066-removebg-preview.png')}}" height="500" alt="">
                </div>
                <div class="col-md-6">
                    <div class="card card-primary">
                        <div class="card-body p-0">
                            <!-- THE CALENDAR -->
                            <div id="calendar"></div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                </div>
{{--                <div class="col-md-3 col-sm-6 col-12">--}}
{{--                    <a href="{{ route('applications.apply') }}" class="link-dark link-offset-2 link-underline-opacity-0">--}}
{{--                        <div class="info-box">--}}
{{--                            <span class="info-box-icon bg-info"><i class="fas fa-folder-open"></i></span>--}}
{{--                            <div class="info-box-content">--}}
{{--                                <span class="info-box-text">Apply for licence</span>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </a>--}}
{{--                </div>--}}
{{--                <div class="col-md-3 col-sm-6 col-12">--}}
{{--                    <a href="{{ route('applications.list') }}" class="link-dark link-offset-2 link-underline-opacity-0">--}}
{{--                        <div class="info-box">--}}
{{--                            <span class="info-box-icon bg-primary"><i class="fas fa-folder"></i></span>--}}

{{--                            <div class="info-box-content">--}}
{{--                                <span class="info-box-text">My Applications</span>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </a>--}}
{{--                </div>--}}
            </div>
        @endif
    @else
        <div class="row">
            <div class="col-md-6">
                    <div class="card h-100 elevation-0 border-0">
                        <div class="card-header">
                            <h3 class="card-title"><strong>Users  & Applications</strong></h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="info-box">
                                        <span class="info-box-icon brand-bg-color elevation-1"><i class="fas fa-user"></i></span>

                                        <div class="info-box-content">
                                            <span class="info-box-text">Users</span>
                                            <span class="info-box-number">{{ $staffCount }}</span>
                                        </div>
                                        <!-- /.info-box-content -->
                                    </div>
                                    <!-- /.info-box -->
                                </div>
                                <div class="col-md-6">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-secondary-subtle elevation-1"><i class="fas fa-user-graduate"></i></span>

                                        <div class="info-box-content">
                                            <span class="info-box-text">Personnel</span>
                                            <span class="info-box-number">{{ $personnelCount }}</span>
                                        </div>
                                        <!-- /.info-box-content -->
                                    </div>
                                    <!-- /.info-box -->
                                </div>
                            </div>
                            <small>Applications</small>
                            <div class="row">
                                <div class="col-md-6 mt-2">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-secondary elevation-1"><i class="fas fa-folder-open"></i></span>

                                        <div class="info-box-content">
                                            <span class="info-box-text">Total</span>
                                            <span class="info-box-number">{{ $totalApplicationsCount }}</span>
                                        </div>
                                        <!-- /.info-box-content -->
                                    </div>
                                    <!-- /.info-box -->
                                </div>
                                <div class="col-md-6 mt-2">
                                    <div class="info-box mb-3">
                                        <span class="info-box-icon bg-info elevation-1"><i class="fas fa-file"></i></span>

                                        <div class="info-box-content">
                                            <span class="info-box-text">Under-Review</span>
                                            <span class="info-box-number">{{ $pendingApplicationsCount }}</span>
                                        </div>
                                        <!-- /.info-box-content -->
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="info-box mb-3">
                                        <span class="info-box-icon bg-success elevation-1"><i class="fas fa-thumbs-up"></i></span>

                                        <div class="info-box-content">
                                            <span class="info-box-text">Approved</span>
                                            <span class="info-box-number">{{ $approvedApplicationsCount }}</span>
                                        </div>
                                        <!-- /.info-box-content -->
                                    </div>
                                    <!-- /.info-box -->
                                </div>
                                <div class="col-md-6">
                                    <div class="info-box mb-3">
                                        <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-thumbs-down"></i></span>

                                        <div class="info-box-content">
                                            <span class="info-box-text">Rejected</span>
                                            <span class="info-box-number">{{ $rejectedApplicationsCount }}</span>
                                        </div>
                                        <!-- /.info-box-content -->
                                    </div>
                                    <!-- /.info-box -->
                                </div>
                            </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card elevation-0 border-0 h-100">
                    <div class="card-header">
                        <h3 class="card-title"><strong>Practising Licences </strong></h3>
                    </div>
                    <div class="card-body">
                        <canvas id="licenseChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="card elevation-0 border-0 h-100">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="info-box">
                                    <span class="info-box-icon "><i class="fas fa-user-md brand-color"></i></span>

                                    <div class="info-box-content">
                                        <span class="info-box-text">Pharmacists</span>
                                        <span class="info-box-number">{{ $pharmacistsCount }}</span>

                                        <div class="progress">
                                            <div class="progress-bar brand-bg-color" style="width:100%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="info-box">
                                    <span class="info-box-icon"><i class="fas fa-stethoscope text-primary"></i></span>

                                    <div class="info-box-content">
                                        <span class="info-box-text">Pharmacy Technologists</span>
                                        <span class="info-box-number">{{ $pharmacyTechniciansCount }}</span>

                                        <div class="progress">
                                            <div class="progress-bar bg-primary" style="width:100%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="info-box">
                                    <span class="info-box-icon"><i class="fas fa-user-nurse text-success"></i></span>

                                    <div class="info-box-content">
                                        <span class="info-box-text">Pharmacy Assistants</span>
                                        <span class="info-box-number">{{ $pharmacyAssistantsCount }}</span>

                                        <div class="progress">
                                            <div class="progress-bar bg-success" style="width:100%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card elevation-0 border-0 h-auto">
                    <div class="card-body">
                       <canvas id="licenseTotalsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    @endif

@endsection


@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@if(Auth::user()->hasAnyRole(['superadmin','Chief Inspector', 'Inspector', 'PRO', 'DG', 'BC']))
<script>
    let ctx = document.getElementById('licenseChart').getContext('2d');
    let chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Pharmacists', 'Pharmacy Technologists', 'Pharmacy Assistants'],
            datasets: [{
                label: 'Active',
                data: [{{$pharmacists_active_licenses}}, {{$pharmacy_technicians_active_licenses}}, {{$pharmacy_assistants_active_licenses}}],
                backgroundColor: 'rgb(5, 47, 107)',
            }, {
                label: 'In-active',
                data: [{{$pharmacists_expired_licenses}}, {{$pharmacy_technicians_expired_licenses}}, {{$pharmacy_assistants_expired_licenses}}],
                backgroundColor: 'rgb(220,53,69)',
            },{
                label: 'Revoked',
                data: [{{$pharmacists_revoked_licenses}}, {{$pharmacy_technicians_revoked_licenses}}, {{$pharmacy_assistants_revoked_licenses}}],
                backgroundColor: 'rgb(67,69,74)',
            },
            ]
        },
        options: {
            responsive: true,
            title: {
                display: true,
                text: 'License Status'
            },
            tooltips: {
                mode: 'index',
                intersect: false,
            },
            hover: {
                mode: 'nearest',
                intersect: true
            },
            scales: {
                xAxes: [{
                    display: true,
                    scaleLabel: {
                        display: true,
                        labelString: 'License Type'
                    }
                }],
                yAxes: [{
                    display: true,
                    scaleLabel: {
                        display: true,
                        labelString: 'Licenses'
                    }
                }]
            },
            animation: {
                duration: 3000, // duration of the animation in milliseconds
                easing: 'easeOutBounce', // easing function to use
            }
        }
    });
</script>
<script>
    let activeLicenses = [{{$totalActiveLicenses}}];
    let inactiveLicenses = [{{$totalExpiredLicenses}}];
    let revokedLicenses = [{{$totalRevokedLicenses}}];

    let ctx2 = document.getElementById('licenseTotalsChart').getContext('2d');
    let chart2 = new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: ['Total Active', 'Total Inactive', 'Total Revoked'],
            datasets: [{
                data: [activeLicenses, inactiveLicenses, revokedLicenses],
                backgroundColor: ['rgb(5, 47, 107)', 'rgb(220,53,69)', 'rgb(67,69,74)'],
            }]
        },
        options: {
            responsive: false,
            plugins: {
                title: {
                    display: true,
                    text: 'Total Licenses:  {{$totalLicenses}}'
                },
                tooltips: {
                    mode: 'index',
                    intersect: false,
                },
                hover: {
                    mode: 'nearest',
                    intersect: true
                },
            },


            animation: {
                duration: 3000, // duration of the animation in milliseconds
                easing: 'easeOutBounce', // easing function to use
            }
        }
    });
</script>
@endif
@if(Auth::user()->hasRole('personnel'))
<script>

    $(function () {
        /* initialize the calendar */
        const Calendar = FullCalendar.Calendar;
        const calendarEl = document.getElementById('calendar');
        const calendar = new Calendar(calendarEl, {
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            themeSystem: 'bootstrap',
            events: [
                {
                    title: '{{ $exam->exam_name }} Personnel Registration Exams',
                    start: '{{ $exam->start_date }}',
                    end: '{{ $exam->end_date }}',
                    backgroundColor: '#f31212',
                    borderColor: '#f39c12',
                    allDay: true
                }
            ],
            editable: false,
            droppable: false, // this allows things to be dropped onto the calendar !!!
            drop: function (info) {
                // is the "remove after drop" checkbox checked?
                if (checkbox.checked) {
                    // if so, remove the element from the "Draggable Events" list
                    info.draggedEl.parentNode.removeChild(info.draggedEl);
                }
            }
        });

        calendar.render();
        // $('#calendar').fullCalendar()

        /* ADDING EVENTS */
        var currColor = '#3c8dbc' //Red by default
    })
</script>
@endif
@endsection
