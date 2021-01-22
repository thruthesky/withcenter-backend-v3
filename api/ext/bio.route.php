<?php

define('BIO_TABLE', 'bio');


class BioRoute {

    /**
     * 최대 in('limit') 명의 사용자 정보를 클라이언트로 리턴한다.
     * 리턴되는 값 중 distance 는 SQL 이 자동으로 생성해 내며, 소수점 세 자리만 남기고 반올림 한다.세 소수점 세자리는 1m 도 안되는 좁은 거리(간격)이다.
     *
     *
     *
     * @attention It will include the login user in the search.
     * @param $in
     *
     *  'latitude', 'longitude', 'km' are optional.
     *    - 'latitude' and 'longitude' are the GEO point of login user.
     *    - If these are set, it will search users within 'km' from the login user's lat & lon.
     *
     *  'fields' - comma(,) separated field names to get.
     *     '*' for all the fields.
     *     'distance' field name is auto generated by SQL server.
     *      i.e) "user_ID, name, distance"
     *      Giving specific fields to slim down the size of return data is recommended.
     *
     *  'birthdate', 'height', 'weight' are range searches.
     *      Its request search params are a pair of '...From' and '...To' like below.
     *      'birthdateFrom', 'birthdateTo', 'heightFrom', 'heightTo', 'weightFrom', 'weightTo',
     *      하나의 짝에서 from, to 하나만 지정 될 수 있다. 예를 들어, heightFrom 없이, heightTo 만 지정 될 수 있다.
     *
     *  'hasProfilePhoto' is set to 'Y', then only users who have profile photo will be returned.
     *
     *  'limit' to limit the record.
     *  'page' is to get what number of page of 'limit' batch. page no begin with 1.
     *
     *  'orderby' is the ORDER BY clause. Ex) "user_ID DESC", "RAND()"
     *
     * @return array|object
     *  - `distance` in km is added to the return records.
     *
     * @example see tests/location.test.php
     *
     * @example
     *  - See phpunit/BioSearchTest.php for the best example.
     * @example
     *    $re = userSearchByLocation(array_merge($rizal, ['km' => 100, 'fields' => 'user_ID,distance']));
     */
    public function search($in) {


        $q_and = [];



        $TABLE = BIO_TABLE;
        $LATITUDE = $in['latitude'] ?? null;
        $LONGITUDE = $in['longitude'] ?? null;
        $DISTANCE_KILOMETERS = $in['km'] ?? null;

        $LIMIT = $in['limit'] ?? 15;
        $q_orderby = isset($in['orderby']) ? "ORDER BY $in[orderby]" : "";

        if ( isset($in['page'] ) ) {
            $LIMIT = "$LIMIT OFFSET " . ($in['page'] - 1) * $LIMIT;
        }

        $FIELDS = isset($in['fields']) ? $in['fields'] : '*';

        foreach(['name', 'gender', 'city', 'drinking', 'smoking', 'hobby'] as $name) {
            if (isset($in[$name])) $q_and[] = "$name='$in[$name]'";
        }

        foreach(['height', 'weight', 'birthdate'] as $name ) {
            $from = $name . "From";
            $to = $name . "To";
            if ( isset($in[$from]) ) $q_and[] = "$name>=$in[$from]";
            if ( isset($in[$to]) ) $q_and[] = "$name<=$in[$to]";
        }

        if ( isset($in['hasProfilePhoto']) && $in['hasProfilePhoto'] == 'Y' ) {
            $q_and[] = "profile_photo_url <> ''";
        }
        $q_geo_from = "";
        if ( $LATITUDE && $LONGITUDE && $DISTANCE_KILOMETERS ) {
            $q_and[] = "distance <= $DISTANCE_KILOMETERS";
            $q_geo_from = <<<EOG
    (
        SELECT *,
        ( 
            ROUND(( 
                ( acos(sin(( $LATITUDE * pi() / 180)) * sin(( `latitude` * pi() / 180)) + cos(( $LATITUDE * pi() /180 ))
                *
                cos(( `latitude` * pi() / 180)) * cos((( $LONGITUDE - `longitude`) * pi()/180)))) * 180/pi()
            ) * 60 * 1.1515 * 1.609344, 3)
        ) as distance FROM $TABLE
    )
EOG;
        }



        if ( $q_and ) $q_where = 'WHERE ' . implode(' AND ', $q_and );
        else $q_where = '';


        $sql=<<<EOS
            SELECT $FIELDS
            FROM $q_geo_from $TABLE
            $q_where
            $q_orderby
            LIMIT $LIMIT
EOS;



        debug_log("SQL: $sql");
        global $wpdb;
        $results = $wpdb->get_results($sql, ARRAY_A);
        if ( $results ) return $results;
        else return [];
    }
}