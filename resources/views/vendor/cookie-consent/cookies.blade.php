<aside id="cookies-policy" class="cookies cookies--no-js"
    data-text="{{ json_encode(__('cookieConsent::cookies.details')) }}">
    <div class="bg-base-300 cookies__alert">
        <div class="cookies__container">
            <div class="cookies__wrapper">
                <h2>@lang('cookieConsent::cookies.title')</h2>
                <div>
                    <p>@lang('cookieConsent::cookies.intro')</p>
                    @if($policy)
                        <p>@lang('cookieConsent::cookies.link', ['url' => $policy])</p>
                    @endif
                </div>
                <div class="cookies__actions">
                    @cookieconsentbutton(action: 'accept.essentials', label: __('cookieConsent::cookies.essentials'), attributes: ['class' => 'btn btn-neutral cookiesBtn cookiesBtn--essentials'])
                    @cookieconsentbutton(action: 'accept.all', label: __('cookieConsent::cookies.all'), attributes: ['class' => 'btn btn-neutral cookiesBtn cookiesBtn--accept'])
                </div>
                <div class="divider p-2"></div>
            </div>
        </div>
        <a href="#cookies-policy-customize" class="cookies__btn cookies__btn--customize">
            <span>@lang('cookieConsent::cookies.customize')</span>
            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"
                aria-hidden="true">
                <path
                    d="M14.7559 11.9782C15.0814 11.6527 15.0814 11.1251 14.7559 10.7996L10.5893 6.63297C10.433 6.47669 10.221 6.3889 10 6.38889C9.77899 6.38889 9.56703 6.47669 9.41075 6.63297L5.24408 10.7996C4.91864 11.1251 4.91864 11.6527 5.24408 11.9782C5.56951 12.3036 6.09715 12.3036 6.42259 11.9782L10 8.40074L13.5774 11.9782C13.9028 12.3036 14.4305 12.3036 14.7559 11.9782Z"
                    fill="currentColor" />
            </svg>
        </a>
        <div class="cookies__expandable" id="cookies-policy-customize">
            <form action="{{ route('cookieconsent.accept.configuration') }}" method="post" class="cookies__customize">
                @csrf
                <div class="cookies__sections">
                    @foreach($cookies->getCategories() as $category)
                        <div class="cookies__section">
                            <label for="cookies-policy-check-{{ $category->key() }}" class="cookies__category">
                                @if ($category->key() === 'essentials')
                                    <input type="hidden" name="categories[]" value="{{ $category->key() }}" class="checkbox" />
                                    <input type="checkbox" name="categories[]" value="{{ $category->key() }}"
                                        id="cookies-policy-check-{{ $category->key() }}" checked="checked"
                                        disabled="disabled" />
                                @else
                                    <input type="checkbox" name="categories[]" value="{{ $category->key() }}"
                                        id="cookies-policy-check-{{ $category->key() }}" />
                                @endif
                                <span class="cookies__box">
                                    <strong>{{ $category->title }}</strong>
                                </span>
                                @if($category->description)
                                    <p>{{ $category->description }}</p>
                                @endif
                            </label>

                            <div class="cookies__expandable" id="cookies-policy-{{ $category->key() }}">
                                <ul class="cookies__definitions">
                                    @foreach($category->getCookies() as $cookie)
                                        <li class="cookies__cookie">
                                            <p class="cookies__name">{{ $cookie->name }}</p>
                                            <p class="cookies__duration">
                                                {{ Carbon\Carbon::now()->diffForHumans(Carbon\Carbon::now()->addMinutes($cookie->duration), true) }}
                                            </p>
                                            @if($cookie->description)
                                                <p class="cookies__description">{{ $cookie->description }}</p>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            <a href="#cookies-policy-{{ $category->key() }}"
                                class="cookies__details">@lang('cookieConsent::cookies.details.more')</a>
                        </div>
                        <div class="divider p-2"></div>
                    @endforeach
                </div>
                <div class="cookies__save">
                    <button type="submit" class="btn btn-neutral w-full">@lang('cookieConsent::cookies.save')</button>
                </div>
            </form>
        </div>
    </div>
</aside>

{{-- STYLES & SCRIPT : feel free to remove them and add your own --}}

<script data-cookie-consent>
    {!! file_get_contents(LCC_ROOT . '/dist/script.js') !!}
</script>
<style data-cookie-consent>
    {!! file_get_contents(LCC_ROOT . '/dist/style.css') !!}

    #cookies-policy.cookies {
        bottom: 48px;
    }

    #cookies-policy.cookies--show .cookies__btn--customize {
        border-bottom: none;
    }

    #cookies-policy .cookies__alert {
        background: var(--color-base-300);
        border: none;

    }

    #cookies-policy .cookies__btn--customize {
        border-top: none;
        color: var(--color-base-content);
    }

    #cookies-policy .cookies__section+.cookies__section {
        border-top: none;

    }

    #cookies-policy .cookies__box:after {
        background: color-mix(in srgb, var(--color-base-content) 20%, var(--color-base-300));
    }

    #cookies-policy .cookies__box:before {
        background: var(--color-base-100);
        border: none;
    }

    #cookies-policy .cookies__category input:checked+.cookies__box:after {
        background: var(--color-base-content);
    }

    #cookies-policy .cookies__save {
        border-top: none;
    }

    #cookies-policy .cookies__details {
        color: var(--color-base-content);
    }

    #cookies-policy .cookies__details:hover {
        color: var(--color-base-content);
        text-decoration: underline;
    }
</style>