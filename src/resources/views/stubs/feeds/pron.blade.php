@php
    use App\Utils\ImagesCrawler;
    use App\Utils\Formatters\Yrl;
@endphp

@extends('stubs.feeds.layouts.yrl')

@section('offer')
    @foreach ($crmObjects as $crmObject)
        @php
            try {
                $path = $crmObject->category == 'flat'
                    ? 'kvartiry-komnaty/flats'
                    : ($crmObject->category == 'house' ? 'doma-dachi-uchastki' : 'kommercheskaya-nedvizhimost');
        @endphp

        @if (in_array($crmObject->category, ['flat', 'house']))
            {{-- Жилая недвижимость --}}
            <offer internal-id="{{ $crmObject->id }}">
                <type>{{ Yrl::type($crmObject) }}</type>
                <property-type>жилая</property-type>
                <category>{{ $category = Yrl::category($crmObject) }}</category>

                @if ($category == 'гараж')
                    <garage-type>{{ Yrl::garageType($crmObject) }}</garage-type>
                @endif

                <lot-number>{{ $crmObject->lot }}</lot-number>
                <url>https://bugrealt.by/{{ $path }}/{{ $crmObject->lot }}</url>
                <creation-date>{{ (new DateTime($crmObject->created_at))->format('c') }}</creation-date>

                <location>
                    @if ($crmObject->jandexGeolocation['country']) <country>{{ $crmObject->jandexGeolocation['country'] }}</country> @endif
                    @if ($crmObject->jandexGeolocation['province']) <region>{{ $crmObject->jandexGeolocation['province'] }}</region> @endif
                    @if ($crmObject->jandexGeolocation['area']) <district>{{ $crmObject->jandexGeolocation['area'] }}</district> @endif
                    @if ($crmObject->jandexGeolocation['locality']) <locality-name>{{ $crmObject->jandexGeolocation['locality'] }}</locality-name> @endif
                    @if ($crmObject->jandexGeolocation['district']) <sub-locality-name>{{ $crmObject->jandexGeolocation['district'] }}</sub-locality-name> @endif
                    @if ($crmObject->jandexGeolocation['street']) <address>{{ $crmObject->jandexGeolocation['street'].($crmObject->jandexGeolocation['house'] ? ', '.$crmObject->jandexGeolocation['house'] : '') }}</address> @endif

                    <latitude>{{ $crmObject->jandexGeolocation['lat'] }}</latitude>
                    <longitude>{{ $crmObject->jandexGeolocation['long'] }}</longitude>
                </location>

                <sales-agent>
                    @if ($crmObject->manager->name) <name>{{ $crmObject->manager->name }}</name> @endif

                    @foreach ($crmObject->manager->phones as $phone)
                        @if (substr($phone, 0, 3) == '375') <phone>+{{ $phone }}</phone> @endif
                    @endforeach

                    <category>агентство</category>
                    <organization>ООО "Бугриэлт"</organization>
                    <url>https://bugrealt.by/</url>
                    @if ($crmObject->manager->email) <email>{{ $crmObject->manager->email }}</email> @endif
                    <photo>https://bugrealt.by/images/logo.jpg</photo>
                </sales-agent>

                @if ($crmObject->price)
                    <price>
                        <value>{{ $crmObject->pricenow ? $crmObject->pricenow : $crmObject->price }}</value>
                        <currency>USD</currency>
                    </price>
                @endif

                <deal-status>прямая продажа</deal-status>

                @if ($crmObject->area)
                    <area>
                        <value>{{ $crmObject->area }}</value>
                        <unit>кв. м</unit>
                    </area>
                @endif

                @if ($crmObject->living_space)
                    <living-space>
                        <value>{{ $crmObject->living_space }}</value>
                        <unit>кв. м</unit>
                    </living-space>
                @endif

                @if ($crmObject->kitchen_area)
                    <kitchen-space>
                        <value>{{ $crmObject->kitchen_area }}</value>
                        <unit>кв. м</unit>
                    </kitchen-space>
                @endif

                @if ($crmObject->land_area)
                    <lot-area>
                        <value>{{ $crmObject->land_area * 100 }}</value>
                        <unit>cотка</unit>
                    </lot-area>
                @endif

                @foreach (ImagesCrawler::crawleImages("https://bugrealt.by/{$path}/{$crmObject->lot}") as $image)
                    <image>{{ $image }}</image>
                @endforeach

                @if ($crmObject->description) <description>{{ $crmObject->description }}</description> @endif

                @if ($crmObject->rooms)
                    <rooms>{{ $crmObject->rooms }}</rooms>
                    <rooms-offered>{{ $crmObject->rooms }}</rooms-offered>
                @endif

                @if ($crmObject->floor_apartment) <floor>{{ $crmObject->floor_apartment }}</floor> @endif
                @if ($balcony = Yrl::balcony($crmObject)) <balcony>{{ $balcony }}</balcony> @endif
                @if ($bathroomUnit = Yrl::bathroomUnit($crmObject)) <bathroom-unit>{{ $bathroomUnit }}</bathroom-unit> @endif
                @if ($crmObject->number_of_floors) <floors-total>{{ $crmObject->number_of_floors }}</floors-total> @endif
                @if ($buildingType = Yrl::buildingType($crmObject)) <building-type>{{ $buildingType }}</building-type> @endif
                @if ($crmObject->year_built) <built-year>{{ $crmObject->year_built }}</built-year> @endif
                @if ($crmObject->electricity) <electricity-supply>да</electricity-supply> @endif
                @if ($crmObject->water_supply) <water-supply>да</water-supply> @endif
                @if ($crmObject->sewerage) <sewerage-supply>да</sewerage-supply> @endif
                @if ($crmObject->heating) <heating-supply>да</heating-supply> @endif
            </offer>
        @else
            {{-- Коммерческая недвижимость --}}
            <offer internal-id="{{ $crmObject->id }}">
                <type>{{ $type = Yrl::type($crmObject) }}</type>
                <category>коммерческая</category>
                <commercial-type>{{ Yrl::commercialType($crmObject) }}</commercial-type>
                <lot-number>{{ $crmObject->lot }}</lot-number>
                <url>https://bugrealt.by/{{ $path }}/{{ $crmObject->lot }}</url>
                <creation-date>{{ (new DateTime($crmObject->created_at))->format('c') }}</creation-date>

                <location>
                    @if ($crmObject->jandexGeolocation['country']) <country>{{ $crmObject->jandexGeolocation['country'] }}</country> @endif
                    @if ($crmObject->jandexGeolocation['province']) <region>{{ $crmObject->jandexGeolocation['province'] }}</region> @endif
                    @if ($crmObject->jandexGeolocation['area']) <district>{{ $crmObject->jandexGeolocation['area'] }}</district> @endif
                    @if ($crmObject->jandexGeolocation['locality']) <locality-name>{{ $crmObject->jandexGeolocation['locality'] }}</locality-name> @endif
                    @if ($crmObject->jandexGeolocation['district']) <sub-locality-name>{{ $crmObject->jandexGeolocation['district'] }}</sub-locality-name> @endif
                    @if ($crmObject->jandexGeolocation['street']) <address>{{ $crmObject->jandexGeolocation['street'].($crmObject->jandexGeolocation['house'] ? ', '.$crmObject->jandexGeolocation['house'] : '') }}</address> @endif

                    <latitude>{{ $crmObject->jandexGeolocation['lat'] }}</latitude>
                    <longitude>{{ $crmObject->jandexGeolocation['long'] }}</longitude>
                </location>

                <sales-agent>
                    @if ($crmObject->manager->name) <name>{{ $crmObject->manager->name }}</name> @endif

                    @foreach ($crmObject->manager->phones as $phone)
                        @if (substr($phone, 0, 3) == '375') <phone>+{{ $phone }}</phone> @endif
                    @endforeach

                    <category>агентство</category>
                    <organization>ООО "Бугриэлт"</organization>
                    <url>https://bugrealt.by/</url>
                    @if ($crmObject->manager->email) <email>{{ $crmObject->manager->email }}</email> @endif
                    <photo>https://bugrealt.by/images/logo.jpg</photo>
                </sales-agent>

                @if ($crmObject->price)
                    @if ($type == 'аренда')
                        <price>
                            <value>{{ $crmObject->pricenow ? $crmObject->pricenow : $crmObject->price }}</value>
                            <currency>USD</currency>
                            <unit>кв. м</unit>
                        </price>
                    @else
                        <price>
                            <value>{{ $crmObject->pricenow ? $crmObject->pricenow : $crmObject->price }}</value>
                            <currency>USD</currency>
                        </price>
                    @endif
                @endif

                @if ($type == 'аренда')
                    <deal-status>direct rent</deal-status>
                @endif

                @if ($crmObject->area)
                    <area>
                        <value>{{ $crmObject->area }}</value>
                        <unit>кв. м</unit>
                    </area>
                @endif

                @foreach (ImagesCrawler::crawleImages("https://bugrealt.by/{$path}/{$crmObject->lot}") as $image)
                    <image>{{ $image }}</image>
                @endforeach

                @if ($crmObject->description) <description>{{ $crmObject->description }}</description> @endif

                @if ($crmObject->rooms) <rooms>{{ $crmObject->rooms }}</rooms> @endif
                @if ($crmObject->floor_apartment) <floor>{{ $crmObject->floor_apartment }}</floor> @endif
                @if ($crmObject->water_supply) <water-supply>да</water-supply> @endif
                @if ($crmObject->sewerage) <sewerage-supply>да</sewerage-supply> @endif
                @if ($crmObject->electricity) <electricity-supply>да</electricity-supply> @endif
            </offer>
        @endif

        @php
            } catch (Throwable $e) {}

            $feedGenerateStatus->increment('generated');
            $feedGenerateStatus->save();
        @endphp
    @endforeach
@endsection
