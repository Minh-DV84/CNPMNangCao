@extends('layouts.layoutmaster')
@section('title', 'Câu hỏi thường gặp')

@push('styles')
    <style>
        .faq-intro {
            background: linear-gradient(135deg, var(--c-header), var(--c-sidebar));
            color: #fff;
            border-radius: 6px;
            padding: 24px;
            margin-bottom: 24px;
        }

        .faq-intro h1 { color: #fff; }
        .accordion-item { border-color: #e1e8f0; }
        .accordion-button { color: var(--c-header); font-weight: 600; }
        .accordion-button:not(.collapsed) { color: #fff; background-color: var(--c-sidebar); box-shadow: none; }
        .accordion-button:focus { border-color: var(--c-accent); box-shadow: 0 0 0 .2rem rgba(245, 166, 35, .2); }
        .accordion-body { color: #5a6b80; background: #fafcff; }
    </style>
@endpush

@section('content')
    <div class="faq-intro">
        <h1 class="mb-2">Câu hỏi thường gặp</h1>
        <p class="mb-0">Giải đáp nhanh các thao tác thường dùng trên HUCE.Web.</p>
    </div>

    <div class="accordion" id="faqAccordion">
        <div class="accordion-item">
            <h2 class="accordion-header" id="faqHeadingOne"><button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faqOne" aria-expanded="true" aria-controls="faqOne">1. Đăng nhập bằng gì?</button></h2>
            <div id="faqOne" class="accordion-collapse collapse show" aria-labelledby="faqHeadingOne" data-bs-parent="#faqAccordion"><div class="accordion-body">Admin và giảng viên đăng nhập bằng email. Sinh viên đăng nhập bằng mã sinh viên 7 chữ số.</div></div>
        </div>
        <div class="accordion-item">
            <h2 class="accordion-header" id="faqHeadingTwo"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqTwo" aria-expanded="false" aria-controls="faqTwo">2. Quên mật khẩu thì sao?</button></h2>
            <div id="faqTwo" class="accordion-collapse collapse" aria-labelledby="faqHeadingTwo" data-bs-parent="#faqAccordion"><div class="accordion-body">Liên hệ quản trị viên để được kiểm tra và cấp lại tài khoản hoặc mật khẩu.</div></div>
        </div>
        <div class="accordion-item">
            <h2 class="accordion-header" id="faqHeadingThree"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqThree" aria-expanded="false" aria-controls="faqThree">3. Mật khẩu mặc định của sinh viên là gì?</button></h2>
            <div id="faqThree" class="accordion-collapse collapse" aria-labelledby="faqHeadingThree" data-bs-parent="#faqAccordion"><div class="accordion-body">Với tài khoản mẫu, mật khẩu mặc định là <strong>SinhVien@123</strong>. Hãy liên hệ quản trị viên nếu tài khoản thực tế không đăng nhập được.</div></div>
        </div>
        <div class="accordion-item">
            <h2 class="accordion-header" id="faqHeadingFour"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqFour" aria-expanded="false" aria-controls="faqFour">4. Nộp bài như thế nào, có nộp lại được không?</button></h2>
            <div id="faqFour" class="accordion-collapse collapse" aria-labelledby="faqHeadingFour" data-bs-parent="#faqAccordion"><div class="accordion-body">Mở bài tập còn hạn, chọn file đúng định dạng rồi bấm Nộp bài. Bạn có thể nộp lại trước hạn; lần nộp mới sẽ thay thế file cũ.</div></div>
        </div>
        <div class="accordion-item">
            <h2 class="accordion-header" id="faqHeadingFive"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqFive" aria-expanded="false" aria-controls="faqFive">5. Xem điểm ở đâu?</button></h2>
            <div id="faqFive" class="accordion-collapse collapse" aria-labelledby="faqHeadingFive" data-bs-parent="#faqAccordion"><div class="accordion-body">Chọn Điểm số trên menu. Sinh viên chỉ xem được bảng điểm của chính mình; giảng viên và admin có thể nhập điểm theo quyền.</div></div>
        </div>
        <div class="accordion-item">
            <h2 class="accordion-header" id="faqHeadingSix"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqSix" aria-expanded="false" aria-controls="faqSix">6. Tại sao tôi bị báo 403?</button></h2>
            <div id="faqSix" class="accordion-collapse collapse" aria-labelledby="faqHeadingSix" data-bs-parent="#faqAccordion"><div class="accordion-body">Bạn đang truy cập chức năng hoặc dữ liệu không thuộc role của mình. Hãy đăng nhập đúng tài khoản hoặc liên hệ quản trị viên.</div></div>
        </div>
        <div class="accordion-item">
            <h2 class="accordion-header" id="faqHeadingSeven"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqSeven" aria-expanded="false" aria-controls="faqSeven">7. Hệ thống chỉ truy cập được giờ nào?</button></h2>
            <div id="faqSeven" class="accordion-collapse collapse" aria-labelledby="faqHeadingSeven" data-bs-parent="#faqAccordion"><div class="accordion-body">Một số chức năng quản trị có thể bị giới hạn theo giờ hành chính. Nếu thấy thông báo từ chối truy cập, hãy thử lại trong khung giờ được hệ thống cho phép.</div></div>
        </div>
        <div class="accordion-item">
            <h2 class="accordion-header" id="faqHeadingEight"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqEight" aria-expanded="false" aria-controls="faqEight">8. Liên hệ ai khi gặp lỗi?</button></h2>
            <div id="faqEight" class="accordion-collapse collapse" aria-labelledby="faqHeadingEight" data-bs-parent="#faqAccordion"><div class="accordion-body">Gửi thông tin tài khoản, URL và ảnh lỗi cho quản trị viên hoặc giảng viên phụ trách để được hỗ trợ.</div></div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
@endsection
