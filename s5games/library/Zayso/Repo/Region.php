<?php
class Zayso_Repo_Region
{
	protected $regionPickList = array(
		128 => '5B-0128 West Knoxville, TN',
		275 => '5B-0275 Knox County West, TN',
		132 => '5E-0132 Spartanburg, SC',
		160 => '5C-0160 Huntsville, AL',
		335 => '5B-0335 Jefferson CO, TN',
		414 => '5F-0414 Cullman, AL',
		498 => '5C-0498 Madison, AL',
		551 => '5B-0551 Pineville, KY',
		605 => '5E-0605 Charlotte, SC',
		772 => '5E-0772 Spartanburg North, SC',
		773 => '5F-0773 Hartselle, AL',
		778 => '5F-0778 Arab, AL',
		894 => '5C-0894 Monrovia, AL',
		914 => '5F-0914 East Limestone, AL',
		916 => '5F-0916 Athens, AL',
		1174=> '5C-1174 NE Madison County, AL',
		1565=> '5C-1565 Ardmore, TN',
		9999=> 'XX-0000 Do not know my region',
	);
	function getRegionPickList() { return $this->regionPickList; }
}
?>