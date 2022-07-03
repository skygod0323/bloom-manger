@extends('adminlte::page')

@section('title', 'Settings')

@section('content_header')
    <h1>Tasks</h1>
@stop

@section('css')
    <style>

        .legend {
            display: inline-block;
            border: 2px solid;
            background-color: transparent;
            height: 15px;
            width: 30px;
            margin-left: 5px;
            vertical-align: middle;
        }

        .seed-color {
            border-color: red;
        }

        .harden-color {
            border-color: blue;
        }

        .transplant-color {
            border-color: blueviolet;
        }

        .harvest-color {
            border-color: yellowgreen;
        }

        .completed-color {
            border-color: green;
        }

    </style>
@stop

@section('content')
    <div class="box">
        <div class="box-body">
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#calendar" aria-controls="calendar" role="tab" data-toggle="tab">Calendar</a></li>
                <li role="presentation"><a href="#list" aria-controls="list" role="tab" data-toggle="tab">List</a></li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="calendar">
                    <div class="row" style="margin-top: 10px; padding: 0px 10px;">
                        <div class="col-md-2">
                            <span>Seed</span>
                            <span class="seed-color legend"></span>
                        </div>
                        <div class="col-md-2">
                            <span>Harden</span>
                            <span class="harden-color legend"></span>
                        </div>
                        <div class="col-md-2">
                            <span>Transplant</span>
                            <span class="transplant-color legend"></span>
                        </div>
                        <div class="col-md-2">
                            <span>Harvest</span>
                            <span class="harvest-color legend"></span>
                        </div>
                        <div class="col-md-2">
                            <span>Completed</span>
                            <span class="completed-color legend"></span>
                        </div>
                    </div>
                    <div id="calendar_ele"></div>
                </div>
                <div role="tabpanel" class="tab-pane " id="list">
                    <div class="row">
                        <div class="col-md-6">
                            <div>
                                <h2>Overdue</h2>
                                @php
                                    $type = 'due';
                                @endphp
                                <ul style="list-style: none">
                                    @foreach ($due_tasks as $index => $task)
                                        
                                        @include('pages.task.task')
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div>
                                <h2>Today</h2>
                                @php
                                    $type = 'today';
                                @endphp
                                <ul style="list-style: none">
                                    @foreach ($today_tasks as $index => $task)
                                        
                                        @include('pages.task.task')
                                    @endforeach
                                </ul>
                            </div>

                            <div>
                                <h2>Tomorrow ({{ $tomorrow_day }})</h2>
                                @php
                                    $type = 'tomorrow';
                                @endphp
                                <ul style="list-style: none">
                                    @foreach ($tomorrow_tasks as $index => $task)
                                        
                                        @include('pages.task.task')
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('complete_multitasks') }}" method="post" id="form_multitasks">
                        @csrf
                        <input type="hidden" id="task_ids" name="task_ids"/>
                    </form>

                    <div style="margin-top: 10px; text-align: right">
                        <button type="button" class="btn-complete btn btn-primary" id="btn-complete">Complete</button>
                    </div>
                </div>

            </div>
        </div>

        <div class="modal fade" tabindex="-1" role="dialog" id="event-modal">
            <form action="{{ route('complete_task') }}" method="post">
                @csrf
                <input type="hidden" id="selected_task" name="task_id"/>
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">Task Detail</h4>
                        </div>
                        <div class="modal-body">
                            <div class="task-content"></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Complete</button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </form>
        </div><!-- /.modal -->

    </div>

@stop

@section('js')
    <script>
        $(function() {


            var tasks = @json($tasks);
            var selected_tasks = [];

            function initCalendar() {
                var events = [];

                for (let i=0; i<tasks.length; i++) {
                    const task = tasks[i];
                    const title = task.type.replace(/^\w/, (c) => c.toUpperCase());

                    let color = ''
                    switch(task.type) {
                        case 'seed':
                            color = 'red'; break;
                        case 'harden':
                            color = 'blue'; break;
                        case 'transplant':
                            color = 'blueviolet'; break;
                        case 'harvest':
                            color = 'yellowgreen'; break;
                    }

                    if (task.completed == 1) {
                        color = 'green';
                    }
                    events.push({
                        title: title + ': ' + task.plant.name,
                        start: task.date,
                        color: color,
                        extendedProps: {
                            plant: tasks[i].plant,
                            type: task.type,
                            task_id: tasks[i].id
                        }
                    })
                }

                var calendarEl = document.getElementById('calendar_ele');

                var calendar = new FullCalendar.Calendar(calendarEl, {
                    // initialDate: '2020-09-12',
                    editable: true,
                    selectable: true,
                    businessHours: true,
                    dayMaxEvents: true, // allow "more" link when too many events
                    events: events,
                    eventClick: function(info) {
                        console.log(info.event.extendedProps);

                        let content = '';
                        switch (info.event.extendedProps.type) {
                            case 'seed':
                                content = `<ul>`;
                                content += `
                                        <li>Plant ${info.event.extendedProps.plant.quantity} seeds</li>
                                        <li>Sow ${info.event.extendedProps.plant.depth}mm deep</li>
                                    `;
                                if (info.event.extendedProps.plant.light == 'yes') 
                                    content += `
                                            <li>Light needed</li>
                                        `;
                                if (info.event.extendedProps.plant.seed_note) 
                                    content += `
                                            <li>${info.event.extendedProps.plant.seed_note}</li>
                                        `;
                                        
                                content += `</ul>`;
                                break;
                                
                            case 'harden':
                                content = `
                                    <ul>
                                        <li>Slowly begin hardening off your ${info.event.extendedProps.plant.name}</li>
                                    </ul>
                                `;
                                break;
                            case 'transplant':
                                content = `<ul>`;
                                content += `
                                        <li>${info.event.extendedProps.plant.name} are ready to transplant outdoors!</li>
                                `;
                                if (info.event.extendedProps.plant.transplant_note) 
                                    content += `
                                            <li>${info.event.extendedProps.plant.transplant_note}</li>
                                        `;

                                content += `</ul>`;
                                break;
                            case 'harvest':
                                content = `<ul>`;
                                content += `
                                    <li>${info.event.extendedProps.plant.name} should be reaching maturity, and soon ready for harvest!</li>
                                `;
                                if (info.event.extendedProps.plant.harvest_note) 
                                    content += `
                                            <li>${info.event.extendedProps.plant.harvest_note}</li>
                                        `;
                                content += `</ul>`;
                                break;
                        }

                        $('#event-modal .task-content').html(content);
                        $('#selected_task').val(info.event.extendedProps.task_id)
                        $('#event-modal').modal('show')
                    }

                });

                calendar.render();
            }


            $('#btn-complete').click(() => {
                const selected_els = document.getElementsByClassName('checkbox-task');
                const selected_tasks = [];
                for (let i=0; i<selected_els.length; i++) {
                    const el = selected_els[i];
                    
                    if (el.checked) {
                        selected_tasks.push(el.getAttribute('data-id'));
                    }
                }

                console.log('selected ids: ', selected_tasks);

                if (selected_tasks.length > 0) {
                    $('#task_ids').val(selected_tasks.join(','));

                    $('#form_multitasks').submit();
                } else {
                    $('#task_ids').val('');

                    alert('Please select at least 1 task to complete.');
                }
            })

            initCalendar();
        })
    </script>
@stop