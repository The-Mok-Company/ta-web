@if ($last > 1)
<nav>
    <ul class="pagination justify-content-center">
        {{-- Previous Page --}}
        @if ($current == 1)
            <li class="page-item disabled">
                <span class="page-link pagination-btn pagination-disabled">
                    <i class="las la-angle-left fs-20 fw-600"></i>
                </span>
            </li>
        @else
            <li class="page-item">
                <a class="page-link page-btn pagination-btn" href="#" data-page="{{ $current - 1 }}">
                    <i class="las la-angle-left fs-20 fw-600"></i>
                </a>
            </li>
        @endif

        {{-- First page --}}
        @if ($current > 4)
            <li class="page-item">
                <a class="page-link page-btn pagination-btn" href="#" data-page="1">1</a>
            </li>
            @if ($current > 5)
                <li class="page-item disabled">
                    <span class="page-link pagination-dots">…</span>
                </li>
            @endif
        @endif

        {{-- Middle pages (3 before and 3 after current) --}}
        @for ($i = max(1, $current - 3); $i <= min($last, $current + 3); $i++)
            @if ($i == $current)
                <li class="page-item active">
                    <span class="page-link pagination-btn pagination-active">{{ $i }}</span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link page-btn pagination-btn" href="#" data-page="{{ $i }}">{{ $i }}</a>
                </li>
            @endif
        @endfor

        {{-- Last page --}}
        @if ($current < $last - 3)
            @if ($current < $last - 4)
                <li class="page-item disabled">
                    <span class="page-link pagination-dots">…</span>
                </li>
            @endif
            <li class="page-item">
                <a class="page-link page-btn pagination-btn" href="#" data-page="{{ $last }}">{{ $last }}</a>
            </li>
        @endif

        {{-- Next Page --}}
        @if ($current < $last)
            <li class="page-item">
                <a class="page-link page-btn pagination-btn" href="#" data-page="{{ $current + 1 }}">
                    <i class="las la-angle-right fs-20 fw-600"></i>
                </a>
            </li>
        @else
            <li class="page-item disabled">
                <span class="page-link pagination-btn pagination-disabled">
                    <i class="las la-angle-right fs-20 fw-600"></i>
                </span>
            </li>
        @endif
    </ul>
</nav>

<style>
    .pagination {
        gap: 8px;
        margin: 30px 0;
    }

    .pagination-btn {
        min-width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 60px;
        font-weight: 500;
        transition: all 0.3s ease;
        background-color: white;
        color: rgba(46, 136, 214, 1);
        border: 1px solid rgba(46, 136, 214, 0.3);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .pagination-btn:hover:not(.pagination-active):not(.pagination-disabled) {
        background-color: rgba(46, 136, 214, 1);
        color: white;
        border-color: rgba(46, 136, 214, 1);
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(46, 136, 214, 0.3);
    }

    .pagination-active {
        background-color: rgba(46, 136, 214, 1) !important;
        color: white !important;
        border-color: rgba(46, 136, 214, 1) !important;
        box-shadow: 0 4px 8px rgba(46, 136, 214, 0.4);
        cursor: default;
    }

    .pagination-disabled {
        background-color: #f8f9fa !important;
        color: #adb5bd !important;
        border-color: #dee2e6 !important;
        cursor: not-allowed;
        box-shadow: none;
    }

    .pagination-dots {
        background-color: transparent !important;
        border: none !important;
        color: #6c757d;
        box-shadow: none;
        cursor: default;
    }

    .page-item {
        margin: 0;
    }

    .page-link {
        text-decoration: none;
    }

    @media (max-width: 576px) {
        .pagination-btn {
            min-width: 35px;
            height: 35px;
            font-size: 14px;
        }

        .pagination {
            gap: 5px;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const pageButtons = document.querySelectorAll('.page-btn');

        pageButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();

                const page = this.getAttribute('data-page');

                // الرجوع لأول الصفحة فوراً
                document.documentElement.scrollTop = 0;
                document.body.scrollTop = 0;
                window.scrollTo(0, 0);

                // تسجيل وقت الضغط
                window.lastPaginationClick = Date.now();

                // تحميل الصفحة الجديدة - استبدل هذا السطر بالكود بتاعك
                // إذا كنت تستخدم AJAX:
                if (typeof loadPage === 'function') {
                    loadPage(page);
                }
                // إذا كنت تستخدم نظام تحميل مخصص:
                else if (typeof window.yourCustomLoadFunction === 'function') {
                    window.yourCustomLoadFunction(page);
                }
                // إذا كنت تستخدم URL parameters:
                else {
                    const currentUrl = new URL(window.location.href);
                    currentUrl.searchParams.set('page', page);
                    window.location.href = currentUrl.toString();
                }
            });
        });
    });

    // التأكد من الرجوع لفوق عند تحميل الصفحة
    window.addEventListener('load', function() {
        document.documentElement.scrollTop = 0;
        document.body.scrollTop = 0;
        window.scrollTo(0, 0);
    });

    setInterval(function() {
        if (window.pageYOffset !== 0 || document.documentElement.scrollTop !== 0) {
            const justClicked = Date.now() - (window.lastPaginationClick || 0) < 500;
            if (justClicked) {
                document.documentElement.scrollTop = 0;
                document.body.scrollTop = 0;
                window.scrollTo(0, 0);
            }
        }
    }, 50);
</script>
@endif
