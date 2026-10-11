@push('styles')
<style>
    .header {
      background: var(--c-header);
      color: #fff;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 24px;
      /* position: sticky giữ header dính khi cuộn trang */
      position: sticky;
      top: 0;
      z-index: 10;
    }

    .header .logo {
      font-size: 20px;
      font-weight: 700;
      letter-spacing: 0.5px;
    }

    .header .logo span {
      color: var(--c-accent);
    }

    .header nav ul {
      display: flex;
      gap: 20px;
      list-style: none;
    }

    .header nav a {
      color: #d9e4f0;
      text-decoration: none;
      font-size: 15px;
      padding: 6px 4px;
      border-bottom: 2px solid transparent;
      transition: color 0.2s, border-color 0.2s;
    }

    .header nav a:hover,
    .header nav a:focus {
      color: #fff;
      border-bottom-color: var(--c-accent);
    }
</style>
@endpush
<header class="header">
      <div class="logo">HUCE<span>.Web</span></div>
      <nav>
        <ul>
          @foreach ($menuHienThi->get('header', []) as $menu)
            <li><a href="{{ $menu->url }}">{{ $menu->ten }}</a></li>
          @endforeach
        </ul>
      </nav>
</header>