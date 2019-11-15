@php
    use App\Utils\ImagesCrawler;
    use App\Utils\Formatters\Kufar;
@endphp

<?php echo '<?xml version="1.0" encoding="UTF-8"?>' ?>

<uedb>
    <records>
        @foreach ($crmObjects as $crmObject)
            @php
                try {
                    $path = $crmObject->category == 'flat'
                        ? 'kvartiry-komnaty/flats'
                        : ($crmObject->category == 'house' ? 'doma-dachi-uchastki' : 'kommercheskaya-nedvizhimost');

                    $phones = array_filter(
                        array_map(function ($phone) {
                            return in_array(substr($phone, 3, 2), ['25', '29', '33', '44', '24'])
                                ? '+'.$phone : null;
                        }, $crmObject->manager->phones)
                    );

                    $images = ImagesCrawler::crawleImages("https://bugrealt.by/{$path}/{$crmObject->lot}");
            @endphp

            <record>
                <unid>{{ $crmObject->id }}</unid>
                <area>{{ Kufar::area($crmObject) }}</area>
                <region>{{ Kufar::region($crmObject) }}</region>
                <remuneration_type>1</remuneration_type>

                @if ($crmObject->title)
                    <subject><![CDATA[
                        {{ mb_substr(str_replace($crmObject->lot, '', $crmObject->title), 0, 30).'... - '.$crmObject->lot }}
                    ]]></subject>
                @endif

                @if ($crmObject->description)
                    <body><![CDATA[
                        {{ mb_substr($crmObject->description, 0, 4000) }}
                    ]]></body>
                @endif

                <category>{{ $category = Kufar::category($crmObject) }}</category>
                <type>{{ Kufar::type($crmObject) }}</type>

                <phone><![CDATA[
                    {{ implode(',', $phones) }}
                ]]></phone>

                @if ($crmObject->price)
                    <price>{{ $crmObject->pricenow ? $crmObject->pricenow : $crmObject->price  }}</price>
                    <currency>USD</currency>
                @endif

                @if ($crmObject->manager->name)
                    <contact_person><![CDATA[
                        {{ mb_substr($crmObject->manager->name, 0, 50) }}
                    ]]></contact_person>
                @endif

                <link><![CDATA[
                    https://bugrealt.by/{{ $path }}/{{ $crmObject->lot }}
                ]]></link>

                @if (! empty($images))
                    <photos>
                        @foreach ($images as $image)
                            <photo picture="{{ $image }}"/>
                        @endforeach
                    </photos>
                @endif

                @switch($category)
                    @case(1010)
                        @if ($crmObject->rooms)
                            <rooms>{{ $crmObject->rooms >= 5 ? 5 : $crmObject->rooms }}</rooms>
                        @endif

                        @if ($crmObject->jandexGeolocation['street']) <address><![CDATA[
                            {{ preg_replace('/["%^()=#+]/', '', $crmObject->jandexGeolocation['street'].($crmObject->jandexGeolocation['house'] ? ', '.$crmObject->jandexGeolocation['house'] : '')) }}
                        ]]></address> @endif

                        <coordinates>{{ $crmObject->jandexGeolocation['lat'] }},{{ $crmObject->jandexGeolocation['long'] }}</coordinates>
                        <condition>1</condition>
                        @if ($crmObject->area) <size>{{ $crmObject->area }}</size> @endif
                        @if ($crmObject->living_space) <size_living_space>{{ $crmObject->living_space }}</size_living_space> @endif
                        @if ($crmObject->kitchen_area) <size_kitchen>{{ $crmObject->kitchen_area }}</size_kitchen> @endif
                        @if ($houseType = Kufar::houseType($crmObject)) <house_type>{{ $houseType }}</house_type> @endif
                        @if ($bathroom = Kufar::bathroom($crmObject)) <bathroom>{{ $bathroom }}</bathroom> @endif
                        @if ($balcony = Kufar::balcony($crmObject)) <balcony>{{ $balcony }}</balcony> @endif
                        @if ($crmObject->year_built)
                            <year_built>{{ $crmObject->year_built <= 1980 ? 1980 : ($crmObject->year_built >= 2025 ? 2025 : $crmObject->year_built) }}</year_built>
                        @endif
                        @if ($crmObject->floor_apartment)
                            <floor>{{ $crmObject->floor_apartment >= 16 ? 16 : $crmObject->floor_apartment }}</floor>
                        @endif

                        @break
                    @case(1020)
                        @if ($crmObject->jandexGeolocation['street']) <address><![CDATA[
                            {{ preg_replace('/["%^()=#+]/', '', $crmObject->jandexGeolocation['street'].($crmObject->jandexGeolocation['house'] ? ', '.$crmObject->jandexGeolocation['house'] : '')) }}
                        ]]></address> @endif

                        <coordinates>{{ $crmObject->jandexGeolocation['lat'] }},{{ $crmObject->jandexGeolocation['long'] }}</coordinates>
                        <condition>1</condition>
                        @if ($crmObject->rooms) <rooms>{{ $crmObject->rooms >= 5 ? 5 : $crmObject->rooms }}</rooms> @endif
                        @if ($crmObject->area) <size>{{ $crmObject->area }}</size> @endif
                        @if ($crmObject->living_space) <size_living_space>{{ $crmObject->living_space }}</size_living_space> @endif
                        @if ($crmObject->kitchen_area) <size_kitchen>{{ $crmObject->kitchen_area }}</size_kitchen> @endif
                        @if ($crmObject->year_built)
                            <year_built>{{ $crmObject->year_built <= 1980 ? 1980 : ($crmObject->year_built >= 2025 ? 2025 : $crmObject->year_built) }}</year_built>
                        @endif
                        @if ($crmObject->land_area) <size_area>{{ $crmObject->land_area * 100 }}</size_area> @endif
                        @if ($wallMaterial = Kufar::wallMaterial($crmObject)) <wall_material>{{ $wallMaterial }}</wall_material> @endif
                        @if ($crmObject->heating) <heating>1</heating> @endif
                        @if ($crmObject->water_supply) <water>1</water> @endif
                        @if ($crmObject->sewerage) <sewage>1</sewage> @endif
                        @if ($crmObject->electricity) <electricity>1</electricity> @endif

                        @break
                    @case(1030)
                        <coordinates>{{ $crmObject->jandexGeolocation['lat'] }},{{ $crmObject->jandexGeolocation['long'] }}</coordinates>

                        @if ($crmObject->jandexGeolocation['street']) <address><![CDATA[
                            {{ preg_replace('/["%^()=#+]/', '', $crmObject->jandexGeolocation['street'].($crmObject->jandexGeolocation['house'] ? ', '.$crmObject->jandexGeolocation['house'] : '')) }}
                        ]]></address> @endif

                        @break
                    @case(1050)
                        <property_type>{{ Kufar::propertyType($crmObject) }}</property_type>

                        @if ($crmObject->jandexGeolocation['street']) <address><![CDATA[
                            {{ preg_replace('/["%^()=#+]/', '', $crmObject->jandexGeolocation['street'].($crmObject->jandexGeolocation['house'] ? ', '.$crmObject->jandexGeolocation['house'] : '')) }}
                        ]]></address> @endif

                        <coordinates>{{ $crmObject->jandexGeolocation['lat'] }},{{ $crmObject->jandexGeolocation['long'] }}</coordinates>
                        <condition>1</condition>
                        @if ($crmObject->area) <size>{{ $crmObject->area }}</size> @endif

                        @break
                    @case(1080)
                        <coordinates>{{ $crmObject->jandexGeolocation['lat'] }},{{ $crmObject->jandexGeolocation['long'] }}</coordinates>

                        @if ($crmObject->jandexGeolocation['street']) <address><![CDATA[
                            {{ preg_replace('/["%^()=#+]/', '', $crmObject->jandexGeolocation['street'].($crmObject->jandexGeolocation['house'] ? ', '.$crmObject->jandexGeolocation['house'] : '')) }}
                        ]]></address> @endif

                        @break
                @endswitch
            </record>

            @php
                } catch (Throwable $e) {}

                $feedGenerateStatus->increment('generated');
                $feedGenerateStatus->save();
            @endphp
        @endforeach
    </records>
</uedb>
