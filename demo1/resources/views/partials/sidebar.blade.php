@push('styles')
<style>
    .sidebar {
      background: var(--c-sidebar);
      color: #d9e4f0;
      padding: 20px 0;
    }

    .sidebar h3 {
      font-size: 12px;
      text-transform: uppercase;
      letter-spacing: 1px;
      color: #8fa9cc;
      padding: 0 20px;
      margin-bottom: 10px;
    }

    .sidebar ul {
      list-style: none;
      margin-bottom: 24px;
    }

    .sidebar a {
      display: block;              /* để cả dòng đều bấm được */
      padding: 10px 20px;
      color: #d9e4f0;
      text-decoration: none;
      border-left: 3px solid transparent;
      transition: background 0.2s, border-color 0.2s;
    }

    .sidebar a:hover,
    .sidebar a.active {
      background: rgba(255, 255, 255, 0.08);
      border-left-color: var(--c-accent);
      color: #fff;
    }
</style>
@endpush
<aside class="sidebar">
      {{-- Menu động lấy từ CSDL, gom theo nhóm --}}
      @foreach (collect($menuHienThi->get('sidebar', []))->groupBy('nhom') as $nhom => $menus)
        @if ($nhom !== '')
          <h3>{{ $nhom }}</h3>
        @endif
        <ul>
          @foreach ($menus as $menu)
            <li><a href="{{ $menu->url }}" @class(['active' => $menu->dangChon()])>{{ $menu->ten }}</a></li>
          @endforeach
        </ul>
      @endforeach
    </aside>