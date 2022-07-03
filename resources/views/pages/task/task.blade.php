<li>
    @if ($task->type == 'seed')
        <h4 class="text-bold">
            <input type="checkbox" class="checkbox-task" id="task-{{$task->id}}" data-id="{{$task->id}}"/>
            <label for="task-{{$task->id}}">Seed {{$task->plant->name}}<label>
        </h4>

        <ul>
            <li>Plant {{$task->plant->quantity}} seeds</li>
            <li>Sow {{$task->plant->depth}}mm deep</li>
            @if ($task->plant->light == 'yes')
                <li>Light needed</li>
            @endif
            @if ($task->plant->seed_note != null)
                <li>{{$task->plant->seed_note}}</li>
            @endif
            @if ($type == 'due')
                <li class="text-danger">Due {{$task->date}}</li>
            @endif
            
        </ul>
    @elseif ($task->type == 'harden')
        <h4 class="text-bold">
            <input type="checkbox" class="checkbox-task" id="task-{{$task->id}}" data-id="{{$task->id}}"/>
            <label for="task-{{$task->id}}">Harden {{$task->plant->name}}</label>
        </h4>

        <ul>
            <li>Slowly begin hardening off your {{$task->plant->name}}</li>
            @if ($type == 'due')
                <li class="text-danger">Due {{$task->date}}</li>
            @endif
        </ul>
    @elseif ($task->type == 'transplant')
        <h4 class="text-bold">
            <input type="checkbox" class="checkbox-task" id="task-{{$task->id}}" data-id="{{$task->id}}"/>
            <label for="task-{{$task->id}}">Transplant {{$task->plant->name}}</label>
        </h4>

        <ul>
            <li>{{$task->plant->name}} are ready to transplant outdoors!</li>
            @if ($task->plant->transplant_note != null)
                <li>{{$task->plant->transplant_note}}</li>
            @endif
            @if ($type == 'due')
                <li class="text-danger">Due {{$task->date}}</li>
            @endif
        </ul>

    @else
        <h4 class="text-bold">
            <input type="checkbox" class="checkbox-task" id="task-{{$task->id}}" data-id="{{$task->id}}"/>
            <label for="task-{{$task->id}}">Harvest {{$task->plant->name}}</label>
        </h4>
        <ul>
            <li>{{$task->plant->name}} should be reaching maturity, and soon ready for harvest!</li>
            @if ($task->plant->harvest_note != null)
                <li>{{$task->plant->harvest_note}}</li>
            @endif
            @if ($type == 'due')
                <li class="text-danger">Due {{$task->date}}</li>
            @endif
        </ul>

    @endif


</li>