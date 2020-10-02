<?php

namespace Tribe\Events\Importer;

require_once 'File_Importer_EventsTest.php';

use Tribe\Events\Test\Factories\Venue;
use Tribe\Events\Test\Factories\Organizer;

class File_Importer_Events_UIDTest extends File_Importer_EventsTest {

	public function setUp() {
		parent::setUp();
		static::factory()->venue     = new Venue();
		static::factory()->organizer = new Organizer();
	}

	public function get_venue_name_and_id() {
		$venue_name = 'Venue Export/Import Business';
		$venue_args = [
			'_VenueAddress'       => '6500 Springfield Mall',
			'_VenueCity'          => 'Springfield',
			'_VenueStateProvince' => 'VA',
			'_VenueZip'           => '22150',
			'_VenueCountry'       => 'US',
			'_tribe_venue_uid'    => 'venueUID1',
		];
		$venue      = $this->factory()->venue->create( [ 'post_title' => $venue_name, 'meta_input' => $venue_args ] );

		return [
			'id'   => $venue,
			'name' => $venue_name,
		];
	}

	public function get_organizer_name_and_id() {
		$organizer_name = 'Organizer Import 1';
		$organizer_args = [
			'_OrganizerEmail'      => '6500 Springfield Mall',
			'_OrganizerWebsite'    => 'Springfield',
			'_OrganizerPhone'      => 'VA',
			'_tribe_organizer_uid' => 'organizerUID1',
		];
		$organizer      = $this->factory()->organizer->create( [ 'post_title' => $organizer_name, 'meta_input' => $organizer_args ] );

		return [
			'id'   => $organizer,
			'name' => $organizer_name,
		];
	}

	/**
	 * @test
	 */
	public function it_should_import_with_no_uid() {

		$sut = $this->make_instance();

		$post_id = $sut->import_next_row();

		$this->assertNotFalse( $post_id );
	}

	/**
	 * @test
	 */
	public function it_should_import_with_a_uid_and_save_to_meta() {

		$sut = $this->make_instance();

		$post_id = $sut->import_next_row();

		var_dump( $post_id );

		$this->assertNotFalse( $post_id );
		$this->assertEquals( 'mt22blue', get_post_meta( $post_id, '_tribe_event_csv_uid', true ) );
	}

	/**
	 * @test
	 */
	public function it_should_update_existing_event_with_uid() {
		$this->data        = [
			'uid_1'         => 'mt22bluetest',
			'name_1'        => 'Modern Tribe\'s Excellent Adventure',
			'start_date_1'  => '11/19/21',
			'end_date_1'    => '11/19/21',
			//'all_day_1' => 'false',
			'description_1' => 'Updated event description',
		];
		$this->field_map[] = 'event_uid';

		$sut = $this->make_instance( 'event-uid' );

		$first_post_id = $sut->import_next_row();
		$this->assertNotFalse( $first_post_id );

		//clean_post_cache( $first_post_id );

		var_dump( $first_post_id );

		$this->data = [
			'uid_1'         => 'mt22bluetest',
			'name_1'        => 'New Event Name',
			'start_date_1'  => '11/25/21',
			'end_date_1'    => '11/25/21',
			//'all_day_1' => 'false',
			'description_1' => 'Updated event description',
		];

		$sut = $this->make_instance( 'event-uid' );

		$second_post_id = $sut->import_next_row();

		var_dump( $second_post_id );

		$this->assertEquals( $first_post_id, $second_post_id );
		$this->assertEquals( 'mt22blue', get_post_meta( $second_post_id, '_tribe_event_csv_uid', true ) );
	}

	/**
	 * @test
	 */
	public function it_should_add_a_venue_using_the_venue_uid() {
		$venue = $this->get_venue_name_and_id();

		$this->data        = [
			'uid_1'         => 'mt22bluetest',
			'name_1'        => 'Modern Tribe\'s Excellent Adventure',
			'start_date_1'  => '11/19/21',
			'end_date_1'    => '11/19/21',
			//'all_day_1' => 'false',
			'description_1' => 'Updated event description',
			'venue_uid_1'   => 'venueUID1',
		];
		$this->field_map[] = 'event_uid';

		$sut = $this->make_instance( 'event-uid' );

		$first_post_id = $sut->import_next_row();

		$this->assertNotFalse( $first_post_id );
		$this->assertEquals( $venue['id'], get_post_meta( $first_post_id, '_EventVenueID', true ) );
	}

	/**
	 * @test
	 */
	public function it_should_add_a_venue_using_the_venue_uid_even_when_venue_name_included() {

	}

	/**
	 * @test
	 */
	public function it_should_add_a_organizer_using_the_organizer_uid() {
		$organizer = $this->get_organizer_name_and_id();

		$this->data        = [
			'uid_1'           => 'mt22bluetest',
			'name_1'          => 'Modern Tribe\'s Excellent Adventure',
			'start_date_1'    => '11/19/21',
			'end_date_1'      => '11/19/21',
			//'all_day_1' => 'false',
			'description_1'   => 'Updated event description',
			'organizer_uid_1' => 'organizerUID1',
		];
		$this->field_map[] = 'event_uid';

		$sut = $this->make_instance( 'event-uid' );

		$first_post_id = $sut->import_next_row();

		$this->assertNotFalse( $first_post_id );
		$this->assertEquals( $organizer['id'], get_post_meta( $first_post_id, '_EventOrganizerID', true ) );
	}

	/**
	 * @test
	 */
	public function it_should_add_multiple_organizers_using_the_organizer_uid() {

	}

	/**
	 * @test
	 */
	public function it_should_add_a_organizer_using_the_organizer_uid_even_when_organizer_id_included() {

	}

	/**
	 * Tests
	 * -support user-provided UIDs in Event, Venue, and Organizer CSV import files
	 *- if a UID is not explicitly set, then CSV imports should keep doing what they do.
	 *
	 *
	 * -verify the post meta is there if uid is included
	 * -update an existing events if uid matches
	 * -still add even if uid is empty
	 *
	 *
	 * -reference VENUE UID or ORGANIZER UID from Events
	 * -if venue uid or organizer uid is present then ignore the venue or organizer name field and only search the uid meta not post id
	 *  event organizers accepts and or id in comma separated list
	 */

}