@php
    $siteSettings = $siteSettings ?? \App\Models\SiteSetting::query()->latest()->first();
    $seo = $seo ?? [];
    $pageFaqs = $pageFaqs ?? collect();

    if (!($pageFaqs instanceof \Illuminate\Support\Collection)) {
        $pageFaqs = collect($pageFaqs);
    }

    $companyName = optional($siteSettings)->company_name ?: config('app.name', 'Clean Source AB');
    $phonePrimary = optional($siteSettings)->phone_primary ?: '+46707413772';
    $email = optional($siteSettings)->email ?: 'info@cleansource.se';

    $addressStreet = optional($siteSettings)->address ?: 'Ångermannagatan 121';
    $postalCode = optional($siteSettings)->postal_code ?: '162 64';
    $city = optional($siteSettings)->city ?: 'Vällingby';
    $serviceAreaName = optional($siteSettings)->service_area ?: 'Stockholm';

    $defaultTitle = optional($siteSettings)->seo_title ?: ($companyName . ' | Städning i Stockholm');
    $defaultDescription = optional($siteSettings)->seo_description ?: 'Professionell hemstädning, flyttstädning, byggstädning, fönsterputsning och storstädning i Stockholm. Tydliga priser och snabb bokning.';

    $title = trim(\Illuminate\Support\Str::squish(strip_tags((string) ($seo['title'] ?? $defaultTitle))));
    $description = trim(\Illuminate\Support\Str::limit(
        \Illuminate\Support\Str::squish(strip_tags((string) ($seo['description'] ?? $defaultDescription))),
        160,
        ''
    ));

    $canonicalUrl = $seo['canonical'] ?? url()->current();
    $siteUrl = url('/');

    $image = $seo['image'] ?? asset('images/logo.png');
    $imageAlt = $seo['image_alt'] ?? ($companyName . ' logotyp');

    $robots = $seo['robots'] ?? 'index,follow,max-image-preview:large,max-snippet:-1,max-video-preview:-1';
    $themeColor = $seo['theme_color'] ?? '#1f2b4d';
    $ogType = $seo['og_type'] ?? 'website';
    $locale = $seo['locale'] ?? 'sv_SE';
    $inLanguage = $seo['in_language'] ?? 'sv-SE';

    $facebookUrl = $seo['facebook_url'] ?? 'https://www.facebook.com/profile.php?id=61558309301151#';
    $instagramUrl = $seo['instagram_url'] ?? 'https://www.instagram.com/cleansource_ab';
    $recoUrl = $seo['reco_url'] ?? 'https://www.reco.se/clean-source-ab';

    $sameAs = array_values(array_filter([
        $facebookUrl,
        $instagramUrl,
        $recoUrl,
    ]));

    $jsonFlags = JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT;

    $localBusinessSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'LocalBusiness',
        '@id' => $siteUrl . '#localbusiness',
        'name' => $companyName,
        'url' => $siteUrl,
        'image' => $image,
        'logo' => asset('images/logo.png'),
        'description' => $description,
        'telephone' => preg_replace('/\s+/', '', $phonePrimary),
        'email' => $email,
        'address' => [
            '@type' => 'PostalAddress',
            'streetAddress' => $addressStreet,
            'postalCode' => $postalCode,
            'addressLocality' => $city,
            'addressCountry' => 'SE',
        ],
        'areaServed' => [
            '@type' => 'AdministrativeArea',
            'name' => $serviceAreaName,
        ],
        'sameAs' => $sameAs,
    ];

    $websiteSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'WebSite',
        '@id' => $siteUrl . '#website',
        'url' => $siteUrl,
        'name' => $companyName,
        'inLanguage' => $inLanguage,
        'publisher' => [
            '@id' => $siteUrl . '#localbusiness',
        ],
    ];

    $serviceSchema = null;

    if (($seo['schema_type'] ?? null) === 'service') {
        $serviceName = $seo['service_name'] ?? $title;
        $serviceType = $seo['service_type'] ?? $serviceName;

        $serviceSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'Service',
            'name' => $serviceName,
            'serviceType' => $serviceType,
            'description' => $description,
            'url' => $canonicalUrl,
            'areaServed' => [
                '@type' => 'AdministrativeArea',
                'name' => $serviceAreaName,
            ],
            'provider' => [
                '@id' => $siteUrl . '#localbusiness',
            ],
            'image' => $image,
        ];
    }

    $breadcrumbSchema = null;

    if (!empty($seo['breadcrumbs']) && is_array($seo['breadcrumbs'])) {
        $breadcrumbItems = collect($seo['breadcrumbs'])
            ->filter(fn ($item) => !empty($item['name']) && !empty($item['url']))
            ->values()
            ->map(function ($item, $index) {
                return [
                    '@type' => 'ListItem',
                    'position' => $index + 1,
                    'name' => $item['name'],
                    'item' => $item['url'],
                ];
            })
            ->all();

        if (!empty($breadcrumbItems)) {
            $breadcrumbSchema = [
                '@context' => 'https://schema.org',
                '@type' => 'BreadcrumbList',
                'itemListElement' => $breadcrumbItems,
            ];
        }
    }

    $faqSchemaItems = $pageFaqs
        ->map(function ($faq) {
            $question = is_array($faq) ? ($faq['question'] ?? '') : ($faq->question ?? '');
            $answer = is_array($faq) ? ($faq['answer'] ?? '') : ($faq->answer ?? '');

            $question = trim(strip_tags((string) $question));
            $answer = trim(strip_tags((string) $answer));

            if ($question === '' || $answer === '') {
                return null;
            }

            return [
                '@type' => 'Question',
                'name' => $question,
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => $answer,
                ],
            ];
        })
        ->filter()
        ->values()
        ->all();

    $faqSchema = !empty($faqSchemaItems)
        ? [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => $faqSchemaItems,
        ]
        : null;
@endphp

<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="csrf-token" content="{{ csrf_token() }}" />
<meta name="robots" content="{{ $robots }}" />
<meta name="theme-color" content="{{ $themeColor }}" />
<meta name="author" content="{{ $companyName }}" />

<title>{{ $title }}</title>
<meta name="description" content="{{ $description }}" />
<link rel="canonical" href="{{ $canonicalUrl }}" />

<meta property="og:locale" content="{{ $locale }}" />
<meta property="og:type" content="{{ $ogType }}" />
<meta property="og:title" content="{{ $title }}" />
<meta property="og:description" content="{{ $description }}" />
<meta property="og:url" content="{{ $canonicalUrl }}" />
<meta property="og:site_name" content="{{ $companyName }}" />
<meta property="og:image" content="{{ $image }}" />
<meta property="og:image:secure_url" content="{{ $image }}" />
<meta property="og:image:alt" content="{{ $imageAlt }}" />

<meta name="twitter:card" content="summary_large_image" />
<meta name="twitter:title" content="{{ $title }}" />
<meta name="twitter:description" content="{{ $description }}" />
<meta name="twitter:image" content="{{ $image }}" />
<meta name="twitter:image:alt" content="{{ $imageAlt }}" />

<link rel="icon" href="{{ asset('images/favicon.ico') }}" sizes="any">
<link rel="icon" type="image/png" href="{{ asset('images/favicon-32x32.png') }}" sizes="32x32">

<script type="application/ld+json">
{!! json_encode($localBusinessSchema, $jsonFlags) !!}
</script>

<script type="application/ld+json">
{!! json_encode($websiteSchema, $jsonFlags) !!}
</script>

@if($serviceSchema)
<script type="application/ld+json">
{!! json_encode($serviceSchema, $jsonFlags) !!}
</script>
@endif

@if($breadcrumbSchema)
<script type="application/ld+json">
{!! json_encode($breadcrumbSchema, $jsonFlags) !!}
</script>
@endif

@if($faqSchema)
<script type="application/ld+json">
{!! json_encode($faqSchema, $jsonFlags) !!}
</script>
@endif