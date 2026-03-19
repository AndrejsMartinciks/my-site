<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\SiteSetting;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class ServicePageController extends Controller
{
    public function private(string $slug)
    {
        $pages = $this->privatePages();

        abort_unless(array_key_exists($slug, $pages), 404);

        $siteSettings = SiteSetting::query()->latest()->first();
        $page = $pages[$slug];

        return view('services.show', [
            'siteSettings' => $siteSettings,
            'page' => $page,
            'menuGroups' => $this->menuGroups(),
        ]);
    }

    public function company(string $slug)
    {
        $pages = $this->companyPages();

        abort_unless(array_key_exists($slug, $pages), 404);

        $siteSettings = SiteSetting::query()->latest()->first();
        $page = $pages[$slug];

        return view('services.show', [
            'siteSettings' => $siteSettings,
            'page' => $page,
            'menuGroups' => $this->menuGroups(),
        ]);
    }

    private function menuGroups(): array
    {
        return [
            'private' => [
                ['label' => 'Hemstädning', 'route' => route('services.private.show', 'hemstadning')],
                ['label' => 'Flyttstädning', 'route' => route('services.private.show', 'flyttstadning')],
                ['label' => 'Fönsterputsning', 'route' => route('window-cleaning')],
                ['label' => 'Byggstädning', 'route' => route('services.private.show', 'byggstadning')],
                ['label' => 'Storstädning', 'route' => route('services.private.show', 'storstadning')],
                ['label' => 'Visningsstädning', 'route' => route('services.private.show', 'visningsstadning')],
            ],
            'company' => [
                ['label' => 'Butiksstädning', 'route' => route('services.company.show', 'butiksstadning')],
                ['label' => 'Flyttstädning', 'route' => route('services.company.show', 'flyttstadning')],
                ['label' => 'Storstädning', 'route' => route('services.company.show', 'storstadning')],
                ['label' => 'Fönsterputsning', 'route' => route('services.company.show', 'fonsterputsning')],
                ['label' => 'Byggstädning', 'route' => route('services.company.show', 'byggstadning')],
                ['label' => 'Kontorsstädning', 'route' => route('services.company.show', 'kontorsstadning')],
            ],
        ];
    }

    private function privatePages(): array
    {
        return [
            'hemstadning' => [
                'group' => 'private',
                'title' => 'Hemstädning i Stockholm',
                'eyebrow' => 'Städning för hemmet',
                'lead' => 'Regelbunden hemstädning som ger dig mer tid över i vardagen och ett rent hem att trivas i.',
                'intro' => [
                    'Vår hemstädning passar dig som vill ha en trygg, återkommande lösning med tydlig kommunikation och jämn kvalitet.',
                    'Vi anpassar upplägget efter din bostad, dina önskemål och hur ofta du vill ha hjälp. Målet är att skapa en enkel vardag där städningen bara fungerar.',
                ],
                'highlights' => [
                    'Återkommande städning varje vecka, varannan vecka eller enligt överenskommelse',
                    'Tydligt upplägg och pris beroende på bostadens storlek och intervall',
                    'Möjlighet att kombinera med tilläggstjänster',
                ],
                'included_title' => 'Det här ingår ofta i hemstädning',
                'included' => [
                    'Dammsugning och våttorkning av golv',
                    'Rengöring av kökets ytor och vitvaror utvändigt',
                    'Städning av badrum och toalett',
                    'Dammtorkning av fria ytor',
                ],
                'why_title' => 'Varför välja vår hemstädning?',
                'why_text' => 'Du får en pålitlig städlösning med fokus på kvalitet, flexibilitet och enkel kontakt när dina behov förändras.',
            ],

            'flyttstadning' => [
                'group' => 'private',
                'title' => 'Flyttstädning i Stockholm',
                'eyebrow' => 'Noggrann städning inför flytt',
                'lead' => 'En genomarbetad flyttstädning som hjälper dig lämna bostaden i ett välstädat skick.',
                'intro' => [
                    'Flyttstädning kräver mer tid och noggrannhet än vanlig städning. Därför arbetar vi strukturerat med tydliga moment för att ge ett rent och tryggt slutresultat.',
                    'Vi hjälper både inför utflyttning och i samband med tillträde när bostaden behöver en ordentlig genomgång.',
                ],
                'highlights' => [
                    'Fast pris utifrån bostadens storlek',
                    'Noggrant upplägg med fokus på detaljer',
                    'Enklare planering inför tillträde och besiktning',
                ],
                'included_title' => 'Exempel på moment',
                'included' => [
                    'Rengöring av kök, luckor, skåp och vitvaror',
                    'Badrum, dusch, toalett och kaklade ytor',
                    'Golvrengöring och dammtorkning i hela bostaden',
                    'Fönsterputsning kan kombineras vid behov',
                ],
                'why_title' => 'Enklare flytt med rätt hjälp',
                'why_text' => 'Vi hjälper dig minska stressen runt flytten genom att ta hand om ett av de mest tidskrävande momenten.',
            ],

            'byggstadning' => [
                'group' => 'private',
                'title' => 'Byggstädning för privatpersoner',
                'eyebrow' => 'Efter renovering och ombyggnad',
                'lead' => 'När arbetet är klart hjälper vi dig att få bort byggdamm, smuts och rester så att bostaden blir trivsam igen.',
                'intro' => [
                    'Efter renovering samlas ofta damm och partiklar på fler ytor än man först tror. Vi hjälper dig med en noggrann genomgång för att göra hemmet redo att användas.',
                    'Byggstädning kan anpassas både för mindre renoveringar och större projekt i bostaden.',
                ],
                'highlights' => [
                    'Anpassad städning efter typ av projekt',
                    'Fokus på byggdamm, ytor och detaljer',
                    'Passar efter målning, köksrenovering och ombyggnad',
                ],
                'included_title' => 'Detta fokuserar vi på',
                'included' => [
                    'Borttagning av byggdamm från fria ytor',
                    'Dammsugning och golvrengöring',
                    'Rengöring av kök, badrum och synliga detaljer',
                    'Kompletterande fönsterputs vid behov',
                ],
                'why_title' => 'Ett tydligare avslut på renoveringen',
                'why_text' => 'När byggprojektet är klart hjälper vi dig att få ett rent, fräscht och mer färdigt resultat.',
            ],

            'storstadning' => [
                'group' => 'private',
                'title' => 'Storstädning i Stockholm',
                'eyebrow' => 'Grundlig genomgång av hemmet',
                'lead' => 'För dig som vill ge bostaden en extra noggrann städning utöver den vanliga vardagsnivån.',
                'intro' => [
                    'Storstädning passar när hemmet behöver en ordentlig genomgång och mer tid läggs på detaljer, ytor och moment som inte alltid ingår i vardagsstädningen.',
                    'Tjänsten är uppskattad både som engångsinsats och som start innan återkommande hemstädning.',
                ],
                'highlights' => [
                    'Fast pris beroende på bostadens storlek',
                    'Mer tid till detaljer och svåråtkomliga ytor',
                    'Kan kombineras med tillägg som fönsterputsning',
                ],
                'included_title' => 'Vanliga moment i storstädning',
                'included' => [
                    'Grundlig rengöring av kök och badrum',
                    'Dammtorkning av fler ytor och detaljer',
                    'Rengöring av lister, kontakter och synliga kanter',
                    'Golvrengöring i hela bostaden',
                ],
                'why_title' => 'När vanlig städning inte riktigt räcker',
                'why_text' => 'Storstädning ger hemmet en tydligare nystart och passar särskilt bra inför säsongsbyte, gäster eller omstart i vardagen.',
            ],

            'visningsstadning' => [
                'group' => 'private',
                'title' => 'Visningsstädning i Stockholm',
                'eyebrow' => 'Inför fotografering och visning',
                'lead' => 'En välstädad bostad gör ett starkare första intryck när det är dags för bilder, visning eller försäljning.',
                'intro' => [
                    'Vid visningsstädning ligger fokus på att skapa ett rent, ordnat och inbjudande helhetsintryck. Tjänsten passar inför både fotografering och öppna visningar.',
                    'Vi hjälper dig lyfta fram bostadens bästa sidor genom en noggrann, representativ städning.',
                ],
                'highlights' => [
                    'Anpassad inför visning eller styling',
                    'Rent, ljust och mer representativt intryck',
                    'Kan kombineras med fönsterputsning',
                ],
                'included_title' => 'Fokusområden',
                'included' => [
                    'Kök och badrum i representativt skick',
                    'Dammsugning, golvrengöring och dammtorkning',
                    'Synliga detaljer och fria ytor prioriteras',
                    'Extra fokus på helhetsintryck',
                ],
                'why_title' => 'För ett bättre första intryck',
                'why_text' => 'En väl genomförd visningsstädning hjälper bostaden att kännas mer välskött, ljus och inbjudande för spekulanter.',
            ],
        ];
    }

    private function companyPages(): array
    {
        return [
            'butiksstadning' => [
                'group' => 'company',
                'title' => 'Butikstädning i Stockholm',
                'eyebrow' => 'Städning för retail och butiksmiljö',
                'lead' => 'En ren och välskött butik bidrar till ett bättre kundintryck och en mer trivsam arbetsmiljö.',
                'intro' => [
                    'Vi hjälper butiker att hålla en jämn och professionell standard i lokalen, oavsett om behovet gäller daglig städning eller löpande underhåll.',
                    'Butikstädning kan anpassas efter öppettider, kundflöde och lokalens förutsättningar.',
                ],
                'highlights' => [
                    'Anpassat efter butikens öppettider',
                    'Fokus på kundytor, entré och personalutrymmen',
                    'Passar både mindre butiker och större handelsytor',
                ],
                'included_title' => 'Vanliga insatser',
                'included' => [
                    'Golvvård och rengöring av kundytor',
                    'Entré, kassazon och synliga ytor',
                    'Personalutrymmen och hygienutrymmen',
                    'Löpande underhåll enligt överenskommelse',
                ],
                'why_title' => 'För ett professionellt intryck varje dag',
                'why_text' => 'Ren butiksmiljö skapar trygghet för både kunder och personal och stärker helhetsintrycket av verksamheten.',
            ],

            'flyttstadning' => [
                'group' => 'company',
                'title' => 'Flyttstädning för företag',
                'eyebrow' => 'Vid lokalflytt eller ometablering',
                'lead' => 'Vi hjälper företag med noggrann flyttstädning när kontor, butik eller annan lokal ska lämnas över.',
                'intro' => [
                    'Vid en företagsflytt är det viktigt att städningen blir tydligt planerad och genomförd med hänsyn till tid, tillgänglighet och lokalens skick.',
                    'Vi arbetar strukturerat för att underlätta överlämning och minska belastningen för verksamheten.',
                ],
                'highlights' => [
                    'Passar kontor, butik och andra verksamhetslokaler',
                    'Planeras utifrån tillträde och överlämning',
                    'Tydligt upplägg före start',
                ],
                'included_title' => 'Exempel på moment',
                'included' => [
                    'Rengöring av kök, pentry och personalytor',
                    'Toaletter och hygienutrymmen',
                    'Golvrengöring och dammtorkning',
                    'Anpassning efter lokaltyp',
                ],
                'why_title' => 'Smidigare avslut vid flytt',
                'why_text' => 'En väl planerad flyttstädning gör det enklare att lämna lokalen i gott skick och fokusera på nästa steg i verksamheten.',
            ],

            'storstadning' => [
                'group' => 'company',
                'title' => 'Storstädning för företag',
                'eyebrow' => 'När lokalen behöver mer än vanlig städning',
                'lead' => 'Storstädning för verksamheter som vill höja standarden i lokalen och skapa en mer välvårdad arbetsmiljö.',
                'intro' => [
                    'För företag kan storstädning vara ett bra komplement till den löpande städningen när vissa ytor eller moment kräver extra tid och fokus.',
                    'Vi planerar insatsen utifrån lokalens användning, belastning och vad som behöver prioriteras.',
                ],
                'highlights' => [
                    'Kompletterar den ordinarie städningen',
                    'Passar kontor, butik och andra verksamhetsytor',
                    'Fokus på detaljer och grundligare genomgång',
                ],
                'included_title' => 'Vanliga delar',
                'included' => [
                    'Djupare rengöring av gemensamma ytor',
                    'Fokus på synliga detaljer och kontaktpunkter',
                    'Kök, hygienutrymmen och golv',
                    'Planeras efter verksamhetens behov',
                ],
                'why_title' => 'Mer välvårdad arbetsmiljö',
                'why_text' => 'En ordentlig storstädning förbättrar intrycket av lokalen och skapar bättre trivsel för personal och besökare.',
            ],

            'fonsterputsning' => [
                'group' => 'company',
                'title' => 'Fönsterputsning för företag',
                'eyebrow' => 'För kontor, butik och kommersiella lokaler',
                'lead' => 'Rena fönster gör stor skillnad för helhetsintrycket i arbetsmiljön och hur verksamheten upplevs av kunder och besökare.',
                'intro' => [
                    'Vi hjälper företag med professionell fönsterputsning för både mindre lokaler och större verksamhetsytor.',
                    'Tjänsten planeras utifrån lokalens behov, fönstrets omfattning och hur ofta ni vill ha hjälp.',
                ],
                'highlights' => [
                    'För kontor, butik och andra företagsmiljöer',
                    'Kan bokas som enstaka insats eller återkommande',
                    'Ger ett renare och mer representativt intryck',
                ],
                'included_title' => 'Passar bland annat för',
                'included' => [
                    'Kontorslokaler och entrépartier',
                    'Butiksfönster och kundnära miljöer',
                    'Invändig och utvändig putsning enligt upplägg',
                    'Kombination med annan företagsstädning',
                ],
                'why_title' => 'Ett tydligare första intryck',
                'why_text' => 'Rena fönster förbättrar både känslan i lokalen och hur verksamheten uppfattas utifrån.',
            ],

            'byggstadning' => [
                'group' => 'company',
                'title' => 'Byggstädning för företag',
                'eyebrow' => 'Efter ombyggnad, renovering eller projekt',
                'lead' => 'När arbetet är klart hjälper vi er att få lokalen ren, representativ och redo att användas.',
                'intro' => [
                    'Efter bygg- eller renoveringsprojekt behövs ofta en mer riktad städinsats för att få bort damm, rester och smuts från lokalerna.',
                    'Vi hjälper företag och fastighetsrelaterade verksamheter att skapa ett tydligare avslut på projektet.',
                ],
                'highlights' => [
                    'Efter renovering, ombyggnad eller anpassning',
                    'Fokus på byggdamm och ytor som behöver återställas',
                    'Kan planeras nära projektets slutskede',
                ],
                'included_title' => 'Vanliga moment',
                'included' => [
                    'Borttagning av byggdamm från fria ytor',
                    'Golvrengöring och dammsugning',
                    'Rengöring av kök, hygienutrymmen och detaljer',
                    'Anpassning efter lokaltyp och projekt',
                ],
                'why_title' => 'Redo för nästa steg',
                'why_text' => 'Byggstädning hjälper verksamheten att snabbt komma vidare efter projektets slut och ta lokalen i bruk.',
            ],

            'kontorsstadning' => [
                'group' => 'company',
                'title' => 'Kontorsstädning i Stockholm',
                'eyebrow' => 'För en trivsam och professionell arbetsmiljö',
                'lead' => 'Kontorsstädning som skapar bättre trivsel för personalen och ett mer välkomnande intryck för besökare.',
                'intro' => [
                    'Vi erbjuder kontorsstädning för företag som vill ha en stabil och välfungerande lösning i vardagen.',
                    'Upplägget kan anpassas efter kontorets storlek, användning och hur ofta ni vill ha städning utförd.',
                ],
                'highlights' => [
                    'Löpande städning för kontor och arbetsplatser',
                    'Anpassas efter verksamhetens rytm och behov',
                    'Fokus på ordning, hygien och helhetsintryck',
                ],
                'included_title' => 'Vanliga delar i kontorsstädning',
                'included' => [
                    'Arbetsytor, gemensamma utrymmen och entré',
                    'Kök, pentry och toaletter',
                    'Golvvård, dammtorkning och pappershantering',
                    'Tydlig plan för återkommande underhåll',
                ],
                'why_title' => 'Ren arbetsmiljö som fungerar i vardagen',
                'why_text' => 'Kontorsstädning bidrar till bättre trivsel, ett starkare intryck och en vardag som flyter smidigare för hela verksamheten.',
            ],
        ];
    }
}