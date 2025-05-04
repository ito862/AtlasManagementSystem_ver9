<x-sidebar>
  <div class="w-100 vh-100 d-flex" style="align-items:center; justify-content:center;">
    <div class="calendar_back border p-5">
      <div class="calendar_back m-auto">
        <h3 class="calendar_title">{{ $calendar->getTitle() }}</h3>
        {!! $calendar->render() !!}
      </div>
    </div>
  </div>
</x-sidebar>
