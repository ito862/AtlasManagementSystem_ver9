<x-sidebar>
  <div class="vh-100 d-flex" style="align-items:center; justify-content:center;">
    <div class="w-50 m-auto h-75">
      <p><span> {{ $date }} </span><span class="ml-3"> {{ $part }} 部</span></p>
      <div class="h-75 border">
        <table class="">
          <tr class="text-center" style="background-color:#04AAD2; color:#fff;">
            <th class="w-25">ID</th>
            <th class="w-25">名前</th>
            <th class="w-25">場所</th>
          </tr>
          @foreach($users as $user)
          <tr class="text-center" style="background-color: {{ $loop->odd ? '#fff' : '#E5F6FA' }}">
            <td class="w-25">{{ $user->id }}</td>
            <td class="w-25">
              <span>{{ $user->over_name }}</span>
              <span>{{ $user->under_name }}</span>
            </td>
            <td class="w-25">リモート</td>
          </tr>
          @endforeach
        </table>
      </div>
    </div>
  </div>
</x-sidebar>
