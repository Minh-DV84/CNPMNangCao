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

    .user-info {
      display: flex;
      align-items: center;
      gap: 15px;
    }

    .user-info .user-name {
      font-weight: 600;
    }

    .user-info .user-role {
      font-size: 12px;
      opacity: 0.8;
      background: rgba(255,255,255,0.2);
      padding: 2px 8px;
      border-radius: 10px;
    }

    .btn-logout {
      background: var(--c-accent);
      color: var(--c-header);
      border: none;
      padding: 6px 12px;
      border-radius: 4px;
      font-size: 14px;
      cursor: pointer;
      transition: background 0.2s;
    }

    .btn-logout:hover {
      background: #dd6b20;
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
      @auth
        <div class="user-info">
          <div>
            <span class="user-name">{{ Auth::user()->name }}</span>
            <span class="user-role">{{ Auth::user()->role_label }}</span>
          </div>
          <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn-logout">Đăng xuất</button>
          </form>
        </div>
      @endauth
</header>