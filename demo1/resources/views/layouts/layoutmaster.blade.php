<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title')</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    :root {
      --sidebar-width: 240px;
      --header-height: 64px;
      --footer-height: 56px;

      --c-header: #1e3a5f;
      --c-sidebar: #2c4a7c;
      --c-content: #ffffff;
      --c-footer: #1e3a5f;
      --c-page: #eef2f7;
      --c-text: #333333;
      --c-accent: #f5a623;
    }

    body {
      font-family: "Segoe UI", Arial, sans-serif;
      color: var(--c-text);
      background: var(--c-page);
      line-height: 1.6;
    }

     .layout {
      display: grid;
      grid-template-areas:
        "header  header"
        "sidebar content"
        "footer  footer";
      grid-template-columns: var(--sidebar-width) 1fr;
      grid-template-rows: var(--header-height) 1fr auto;
      min-height: 100vh;
    }

    /* Gán từng vùng cho từng thẻ bằng grid-area */
    .header  { grid-area: header;  }
    .sidebar { grid-area: sidebar; }
    .content { grid-area: content; }
    .footer  { grid-area: footer;  }
 
    /* ============================================================
       4. SIDEBAR
       ============================================================ */
    

    /* ============================================================
       5. CONTENT
       ============================================================ */
    .content {
      background: var(--c-content);
      padding: 28px 32px;
    }

    .content h1 {
      font-size: 26px;
      margin-bottom: 8px;
      color: var(--c-header);
    }

    .content .subtitle {
      color: #6b7c93;
      margin-bottom: 24px;
    }

    .content h2 {
      font-size: 19px;
      margin: 24px 0 10px;
      color: var(--c-header);
    }

    .content p {
      margin-bottom: 12px;
    }

    /* Lưới thẻ card: auto-fit + minmax = responsive KHÔNG cần
       media query. Mỗi cột tối thiểu 200px, thừa chỗ thì tự
       thêm cột mới. */
    .card-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 16px;
      margin-top: 16px;
    }

    .card {
      border: 1px solid #e1e8f0;
      border-radius: 6px;
      padding: 16px;
      background: #fafcff;
    }

    .card h4 {
      color: var(--c-sidebar);
      margin-bottom: 6px;
    }

    .card p {
      font-size: 14px;
      color: #5a6b80;
      margin: 0;
    }
     @media (max-width: 768px) {
      .layout {
        grid-template-areas:
          "header"
          "sidebar"
          "content"
          "footer";
        grid-template-columns: 1fr;
        grid-template-rows: auto auto 1fr auto;
      }

      .sidebar {
        padding: 12px 0;
      }

      /* Sidebar nằm ngang, cuộn ngang nếu nhiều mục */
      .sidebar ul {
        display: flex;
        overflow-x: auto;
        margin-bottom: 0;
      }

      .sidebar h3 { display: none; }

      .sidebar a {
        white-space: nowrap;
        border-left: none;
        border-bottom: 3px solid transparent;
      }

      .sidebar a:hover,
      .sidebar a.active {
        border-left: none;
        border-bottom-color: var(--c-accent);
      }

      .header {
        flex-direction: column;
        height: auto;
        padding: 12px;
        gap: 8px;
        position: static;
      }

      .content { padding: 20px 16px; }
    }
  </style>

</head>

<body>
   <div class="layout">

    <!-- ---------- HEADER ---------- -->
    @include('partials.header')

    <!-- ---------- SIDEBAR ---------- -->
    @include('partials.sidebar')

    <!-- ---------- CONTENT ---------- -->
    <main class="content">
         @yield('content')
    </main>

    <!-- ---------- FOOTER ---------- -->
    @include('partials.footer')

  </div>
    @stack('styles')

</body>
</html>