# Delete 141 142
delete from event_person where event_id = 1979;
delete from event_team   where event_id = 1979;
delete from event        where       id = 1979;

delete from event_person where event_id = 1980;
delete from event_team   where event_id = 1980;
delete from event        where       id = 1980;

delete from event_person where event_id = 1935;
delete from event_team   where event_id = 1935;
delete from event        where       id = 1935;

delete from event_person where event_id = 1981;
delete from event_team   where event_id = 1981;
delete from event        where       id = 1981;

delete from event_person where event_id = 1970;
delete from event_team   where event_id = 1970;
delete from event        where       id = 1970;


update team set desc1 = 'E1 A01C-R0002 Yen'       where id = 1854;
update team set desc1 = 'E2 A01B-R0779 Giannetti' where id = 1856;
update team set desc1 = 'E3 A06D-R0418 Tepper'    where id = 1855;
update team set desc1 = 'E4 A06F-R0399 Lundgren'  where id = 1852;

update team set desc1 = 'F1 A07O-R0178 Bright'  where id = 1866;
update team set desc1 = 'F2 A07E-R0769 Castro'  where id = 1867;
update team set desc1 = 'F3 A09G-R0354 Service' where id = 1868;
update team set desc1 = 'F4 A05B-R0337 Ownby'   where id = 1864;

update team set desc1 = 'G1 A14I-R0644 Freedland'    where id = 1860;
update team set desc1 = 'G2 A05C-R0160 Meehan'       where id = 1861;
update team set desc1 = 'G3 A05C-R0498 Ramirez'      where id = 1862;
update team set desc1 = 'G4 A08H-R0881 Vandekerkhof' where id = 1858;

update team set desc1 = 'H1 A12E-R0368 Linggi' where id = 1869;
update team set desc1 = 'H2 A05B-R0275 Duke'   where id = 1872;
update team set desc1 = 'H3 A14I-R1521 Shipe'  where id = 1873;
update team set desc1 = 'H4 A05B-R1390 Morgan' where id = 1870;

update team set desc1 = 'I1 A05B-R0124 Hendrickson' where id = 1853;
update team set desc1 = 'I2 A05G-R0727 Brown'       where id = 1851;
update team set desc1 = 'I3 A14I-R0864 Hendrick'    where id = 1865;
update team set desc1 = 'I4 A13G-R1475 Gibson'      where id = 1863;
update team set desc1 = 'I5 A05B-R1159 Huffman'     where id = 1859;

update event_team set team_id = null where id = 3890;
update event_team set team_id = null where id = 3891;
update event_team set team_id = null where id = 3892;
update event_team set team_id = null where id = 3893;
update event_team set team_id = null where id = 3894;
update event_team set team_id = null where id = 3895;
update event_team set team_id = null where id = 3896;
update event_team set team_id = null where id = 3897;
update event_team set team_id = null where id = 3898;
update event_team set team_id = null where id = 3899;
update event_team set team_id = null where id = 3899;
update event_team set team_id = null where id = 3831;

update event_team set team_id = 1867 where id = 3890;
update event_team set team_id = 1868 where id = 3891;
update event_team set team_id = 1852 where id = 3892;
update event_team set team_id = 1854 where id = 3893;

update event_team set team_id = 1856 where id = 3894;
update event_team set team_id = 1855 where id = 3895;

update event_team set team_id = 1859 where id = 3956;
update event_team set team_id = 1851 where id = 3957;
update event_team set team_id = 1864 where id = 3860;
update event_team set team_id = 1866 where id = 3861;
update event_team set team_id = 1862 where id = 3863;
update event_team set team_id = 1861 where id = 3862;

update event_team set team_id = 1858 where id = 3864;
update event_team set team_id = 1860 where id = 3865;
update event_team set team_id = 1853 where id = 3926;
update event_team set team_id = 1863 where id = 3927;
update event_team set team_id = 1872 where id = 3928;
update event_team set team_id = 1873 where id = 3929;
update event_team set team_id = 1870 where id = 3930;
update event_team set team_id = 1869 where id = 3931;


