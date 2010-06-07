<?php
class RegionRepo
{
	protected $regionPickList = array
	(
		124 => '5B-0124 Knoxville, TN',
		128 => '5B-0128 West Knoxville, TN',
		275 => '5B-0275 Knox County West, TN',
		297 => '5H-0297 Montgomery, AL',
		132 => '5E-0132 Spartanburg, SC',
		160 => '5C-0160 Huntsville, AL',
		335 => '5B-0335 Jefferson CO, TN',
		337 => '5B-0337 Knoxville North, TN',
		385 => '5G-0385 Hellenwood, TN',
		414 => '5F-0414 Cullman, AL',
		498 => '5C-0498 Madison, AL',
		551 => '5B-0551 Pineville, KY',
		605 => '5E-0605 Charlotte, NC',
		722 => '5E-0722 Spartanburg North, SC',
		773 => '5F-0773 Hartselle, AL',
		778 => '5F-0778 Arab, AL',
		894 => '5C-0894 Monrovia, AL',
		914 => '5F-0914 East Limestone, AL',
		916 => '5F-0916 Athens, AL',
		1062=> '5F-1062 Albertville, AL',
		1174=> '5C-1174 NE Madison County, AL',
		1535=> '5D-1535 Covington, TN',
		1565=> '5C-1565 Ardmore, TN',
		9999=> 'XX-0000 Do not know my region',
	);
	function getRegionPickList() { return $this->regionPickList; }
}
?>