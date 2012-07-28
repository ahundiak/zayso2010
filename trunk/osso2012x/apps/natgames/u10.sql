select event.id as event_id, event.num as num, 
event_team.id as event_team_id, 
event_team.type as event_team_type, event_team.team_id as team_id
from  event
left join event_team on event_team.event_id = event.id
where project_id = 52 and num in (59,60,61,62,63,64,65,66,67,68,69,70);                       

(52,51,50,47,48,49,58,57,56,53,54,55);

+----------+-----+---------------+-----------------+---------+
| event_id | num | event_team_id | event_team_type | team_id |
+----------+-----+---------------+-----------------+---------+
|     1996 |  47 |          3992 | Home            |    1901 | A6 1800
|     1996 |  47 |          3993 | Away            |    1902 | A4 1878
|     1997 |  48 |          3994 | Home            |    1903 | A5 1879
|     1997 |  48 |          3995 | Away            |    1904 | A1 1875
|     1998 |  49 |          3996 | Home            |    1905 | A3 1877
|     1998 |  49 |          3997 | Away            |    1906 | A2 1876

|     2014 |  50 |          4028 | Home            |    1907 | C6 1886
|     2014 |  50 |          4029 | Away            |    1908 | C4 1884
|     2015 |  51 |          4030 | Home            |    1909 | C5 1885
|     2015 |  51 |          4031 | Away            |    1910 | C1 1881
|     2016 |  52 |          4032 | Home            |    1911 | C2 1882
|     2016 |  52 |          4033 | Away            |    1912 | C3 1883

|     2032 |  53 |          4064 | Home            |    1913 | B6 1893
|     2032 |  53 |          4065 | Away            |    1914 | B4 1890
|     2033 |  54 |          4066 | Home            |    1915 | B5 1892
|     2033 |  54 |          4067 | Away            |    1916 | B1 1887
|     2034 |  55 |          4068 | Home            |    1917 | B2 1888
|     2034 |  55 |          4069 | Away            |    1918 | B3 1889

|     2050 |  56 |          4100 | Home            |    1919 | D6 1900
|     2050 |  56 |          4101 | Away            |    1920 | D4 1898
|     2051 |  57 |          4102 | Home            |    1921 | D5 1899
|     2051 |  57 |          4103 | Away            |    1922 | D1 1894
|     2052 |  58 |          4104 | Home            |    1923 | D2 1895
|     2052 |  58 |          4105 | Away            |    1924 | D3 1897

+----------+-----+---------------+-----------------+---------+
52	Jul 06	Fri	15:00 PM	CELL	CELL1	U10G PP C	C2 A02A-R0044 Andrews	C3 A05B-R0279 Lauderback
51	Jul 06	Fri	15:00 PM	CELL	CELL2	U10G PP C	C5 A05C-R0160 Kross	C1 A01H-R0443 Alles
50	Jul 06	Fri	15:00 PM	CELL	CELL3	U10G PP C	C6 A12D-R0310 Jerez	C4 A06D-R0183 Lindsey

select team.id,type,key1,desc1,age,age,gender from team where project_id = 52 and age = 'U10' and gender = 'G' and type='pool';

+------+------+------------+--------------------------+-----+-----+--------+
| id   | type | key1       | desc1                    | age | age | gender |
+------+------+------------+--------------------------+-----+-----+--------+
| 1904 | pool | E1 U10G    | E1 U10G                  | U10 | U10 | G      |
| 1906 | pool | E2 U10G    | E2 U10G                  | U10 | U10 | G      |
| 1905 | pool | E3 U10G    | E3 U10G                  | U10 | U10 | G      |
| 1902 | pool | E4 U10G    | E4 U10G                  | U10 | U10 | G      |
| 1903 | pool | E5 U10G    | E5 U10G                  | U10 | U10 | G      |
| 1901 | pool | E6 U10G    | E6 U10G                  | U10 | U10 | G      |
| 1916 | pool | F1 U10G    | F1 U10G                  | U10 | U10 | G      |
| 1917 | pool | F2 U10G    | F2 U10G                  | U10 | U10 | G      |
| 1918 | pool | F3 U10G    | F3 U10G                  | U10 | U10 | G      |
| 1914 | pool | F4 U10G    | F4 U10G                  | U10 | U10 | G      |
| 1915 | pool | F5 U10G    | F5 U10G                  | U10 | U10 | G      |
| 1913 | pool | F6 U10G    | F6 U10G                  | U10 | U10 | G      |
| 1910 | pool | G1 U10G    | G1 U10G                  | U10 | U10 | G      |
| 1911 | pool | G2 U10G    | G2 U10G                  | U10 | U10 | G      |
| 1912 | pool | G3 U10G    | G3 U10G                  | U10 | U10 | G      |
| 1908 | pool | G4 U10G    | G4 U10G                  | U10 | U10 | G      |
| 1909 | pool | G5 U10G    | G5 U10G                  | U10 | U10 | G      |
| 1907 | pool | G6 U10G    | G6 U10G                  | U10 | U10 | G      |
| 1922 | pool | H1 U10G    | H1 U10G                  | U10 | U10 | G      |
| 1923 | pool | H2 U10G    | H2 U10G                  | U10 | U10 | G      |
| 1924 | pool | H3 U10G    | H3 U10G                  | U10 | U10 | G      |
| 1920 | pool | H4 U10G    | H4 U10G                  | U10 | U10 | G      |
| 1921 | pool | H5 U10G    | H5 U10G                  | U10 | U10 | G      |
| 1919 | pool | H6 U10G    | H6 U10G                  | U10 | U10 | G      |
| 1875 | pool | U10G PP A1 | A1 A01P-R0020 Mearns     | U10 | U10 | G      |
| 1876 | pool | U10G PP A2 | A2 A11L-R0086 Neilsen    | U10 | U10 | G      |
| 1877 | pool | U10G PP A3 | A3 A06D-R0891 Olswang    | U10 | U10 | G      |
| 1878 | pool | U10G PP A4 | A4 A05B-R0551 Collett    | U10 | U10 | G      |
| 1879 | pool | U10G PP A5 | A5 A14I-R1521 Webber     | U10 | U10 | G      |
| 1880 | pool | U10G PP A6 | A6 A05B-R1159 Taylor     | U10 | U10 | G      |
| 1887 | pool | U10G PP B1 | B1 A01U-R0602 Salgado    | U10 | U10 | G      |
| 1888 | pool | U10G PP B2 | B2 A11K-R0143 Decoite    | U10 | U10 | G      |
| 1890 | pool | U10G PP B3 | B3 A05B-R0124 Trapp      | U10 | U10 | G      |
| 1891 | pool | U10G PP B4 | B4 A06D-R0418 Halilic    | U10 | U10 | G      |
| 1892 | pool | U10G PP B5 | B5 A03T-R0095 Laredo     | U10 | U10 | G      |
| 1893 | pool | U10G PP B6 | B6 A073-R0118 Werner     | U10 | U10 | G      |

| 1881 | pool | U10G PP C1 | C1 A01H-R0443 Alles      | U10 | U10 | G      |
| 1882 | pool | U10G PP C2 | C2 A02A-R0044 Andrews    | U10 | U10 | G      |
| 1883 | pool | U10G PP C3 | C3 A05B-R0279 Lauderback | U10 | U10 | G      |
| 1884 | pool | U10G PP C4 | C4 A06D-R0183 Lindsey    | U10 | U10 | G      |
| 1885 | pool | U10G PP C5 | C5 A05C-R0160 Kross      | U10 | U10 | G      |
| 1886 | pool | U10G PP C6 | C6 A12D-R0310 Jerez      | U10 | U10 | G      |

| 1894 | pool | U10G PP D1 | D1 A10W-R0068 Hartnell   | U10 | U10 | G      |
| 1895 | pool | U10G PP D2 | D2 A05B-R0128 Devan      | U10 | U10 | G      |
| 1897 | pool | U10G PP D3 | D3 A05B-R0275 Stratton   | U10 | U10 | G      |
| 1898 | pool | U10G PP D4 | D4 A08B-R0211 Diget      | U10 | U10 | G      |
| 1899 | pool | U10G PP D5 | D5 A14J-R0864 Webb       | U10 | U10 | G      |
| 1900 | pool | U10G PP D6 | D6 A13G-R1475 Hasenecz   | U10 | U10 | G      |
+------+------+------------+--------------------------+-----+-----+--------+
+----------+-----+---------------+-----------------+---------+
| event_id | num | event_team_id | event_team_type | team_id |
+----------+-----+---------------+-----------------+---------+
|     1999 |  59 |          3999 | Away            |    1904 | A3 1877
|     1999 |  59 |          3998 | Home            |    1905 | A1 1875
|     2000 |  60 |          4000 | Home            |    1901 | A6 1880
|     2000 |  60 |          4001 | Away            |    1906 | A2 1876
|     2001 |  61 |          4002 | Home            |    1902 | A4 1878
|     2001 |  61 |          4003 | Away            |    1903 | A5 1879
|     2017 |  62 |          4035 | Away            |    1910 | C3 1883
|     2017 |  62 |          4034 | Home            |    1912 | C1 1881
|     2018 |  63 |          4036 | Home            |    1907 | C6 1886
|     2018 |  63 |          4037 | Away            |    1911 | C2 1882
|     2019 |  64 |          4038 | Home            |    1908 | C4 1884
|     2019 |  64 |          4039 | Away            |    1909 | C5 1885
|     2035 |  65 |          4071 | Away            |    1916 | B3 1890
|     2035 |  65 |          4070 | Home            |    1918 | B1 1887
|     2036 |  66 |          4072 | Home            |    1913 | B6 1893
|     2036 |  66 |          4073 | Away            |    1917 | B2 1888
|     2037 |  67 |          4074 | Home            |    1914 | B4 1891
|     2037 |  67 |          4075 | Away            |    1915 | B5 1892
|     2053 |  68 |          4107 | Away            |    1922 | D3 1897*
|     2053 |  68 |          4106 | Home            |    1924 | D1 1894*
|     2054 |  69 |          4108 | Home            |    1919 | D6 1900*
|     2054 |  69 |          4109 | Away            |    1923 | D2 1895*
|     2055 |  70 |          4110 | Home            |    1920 | D4 1898
|     2055 |  70 |          4111 | Away            |    1921 | D5 1899
+----------+-----+---------------+-----------------+---------+

; U10G Games
update event_team set team_id = 1877 where id = 3998;
update event_team set team_id = 1875 where id = 3999;
update event_team set team_id = 1880 where id = 4000;
update event_team set team_id = 1876 where id = 4001;
update event_team set team_id = 1878 where id = 4002;
update event_team set team_id = 1879 where id = 4003;
update event_team set team_id = 1883 where id = 4034;
update event_team set team_id = 1881 where id = 4035;
update event_team set team_id = 1886 where id = 4036;
update event_team set team_id = 1882 where id = 4037;
update event_team set team_id = 1884 where id = 4038;
update event_team set team_id = 1885 where id = 4039;
update event_team set team_id = 1890 where id = 4070;
update event_team set team_id = 1887 where id = 4071;
update event_team set team_id = 1893 where id = 4072;
update event_team set team_id = 1888 where id = 4073;
update event_team set team_id = 1891 where id = 4074;
update event_team set team_id = 1892 where id = 4075;

update event_team set team_id = 1897 where id = 4106;
update event_team set team_id = 1894 where id = 4107;
update event_team set team_id = 1900 where id = 4108;
update event_team set team_id = 1895 where id = 4109;

update event_team set team_id = 1898 where id = 4110;
update event_team set team_id = 1899 where id = 4111;

; U10G Games
update event_team set team_id = 1880 where id = 3992;
update event_team set team_id = 1878 where id = 3993;
update event_team set team_id = 1879 where id = 3994;
update event_team set team_id = 1875 where id = 3995;
update event_team set team_id = 1877 where id = 3996;
update event_team set team_id = 1876 where id = 3997;


update event_team set team_id = 1893 where id = 4064;
update event_team set team_id = 1891 where id = 4065;
update event_team set team_id = 1892 where id = 4066;
update event_team set team_id = 1887 where id = 4067;
update event_team set team_id = 1888 where id = 4068;
update event_team set team_id = 1890 where id = 4069;

update event_team set team_id = 1886 where id = 4028;
update event_team set team_id = 1884 where id = 4029;
update event_team set team_id = 1885 where id = 4030;
update event_team set team_id = 1881 where id = 4031;
update event_team set team_id = 1882 where id = 4032;
update event_team set team_id = 1883 where id = 4033;

update event_team set team_id = 1900 where id = 4100;
update event_team set team_id = 1898 where id = 4101;
update event_team set team_id = 1899 where id = 4102;
update event_team set team_id = 1894 where id = 4103;
update event_team set team_id = 1895 where id = 4104;
update event_team set team_id = 1897 where id = 4105;

==================================================================================================================
select team.id,type,key1,desc1,age,age,gender from team where project_id = 52 and age = 'U10' and gender = 'B' and type='pool' order by desc1;

===================
update event_team set team_id = 1855 where id = 3872;
update event_team set team_id = 1852 where id = 3873;
update event_team set team_id = 1854 where id = 3902;
update event_team set team_id = 1856 where id = 3903;

===================
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

===
select 
event.id as event_id, event.num as num, event_team.type as event_team_type,
event_team.id as event_team_id, event_team.team_id as teeam_id
from  event
left join event_team on event_team.event_id = event.id
where project_id = 52 and num in (139,138,137,143,144,145,142,141,140,146,147,148);

+----------+-----+-----------------+----------+
| event_id | num | event_team_type | teeam_id |
+----------+-----+-----------------+----------+
|     1945 | 137 | Home            |     1857 | F2 1867
|     1945 | 137 | Away            |     1858 | F3 1868
|     1946 | 138 | Home            |     1859 | E4 1852
|     1946 | 138 | Away            |     1860 | E1 1854
|     1947 | 139 | Home            |     1861 | E2 1856
|     1947 | 139 | Away            |     1862 | E3 1855
|     1978 | 140 | Home            |     1869 | I5 1859
|     1978 | 140 | Away            |     1870 | I2 1851

|     1930 | 143 | Home            |     1851 | F4 1864
|     1930 | 143 | Away            |     1852 | F1 1866
|     1931 | 144 | Home            |     1853 | G2 1861
|     1931 | 144 | Away            |     1854 | G3 1862
|     1932 | 145 | Home            |     1855 | G4 1858
|     1932 | 145 | Away            |     1856 | G1 1860
|     1963 | 146 | Home            |     1863 | I1 1853
|     1963 | 146 | Away            |     1864 | I4 1863
|     1964 | 147 | Home            |     1865 | H2 1872
|     1964 | 147 | Away            |     1866 | H3 1873
|     1965 | 148 | Home            |     1867 | H4 1870
|     1965 | 148 | Away            |     1868 | H1 1869
+----------+-----+-----------------+----------+

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

----------+-----+-----------------+---------------+----------+
 event_id | num | event_team_type | event_team_id | teeam_id |
----------+-----+-----------------+---------------+----------+
     1945 | 137 | Home            |          3890 |     1867 |
     1945 | 137 | Away            |          3891 |     1868 |
     1946 | 138 | Home            |          3892 |     1852 |
     1946 | 138 | Away            |          3893 |     1854 |
     1947 | 139 | Away            |          3895 |     1855 |
     1947 | 139 | Home            |          3894 |     1856 |
     1978 | 140 | Away            |          3957 |     1851 |
     1978 | 140 | Home            |          3956 |     1859 |
     1979 | 141 | Away            |          3959 |     1869 |
     1979 | 141 | Home            |          3958 |     1871 |
     1980 | 142 | Home            |          3960 |     1872 |
     1980 | 142 | Away            |          3961 |     1873 |
     1930 | 143 | Home            |          3860 |     1851 |
     1930 | 143 | Away            |          3861 |     1852 |
     1931 | 144 | Away            |          3863 |     1862 |
     1931 | 144 | Home            |          3862 |     1866 |
     1932 | 145 | Home            |          3864 |     1858 |
     1932 | 145 | Away            |          3865 |     1860 |
     1963 | 146 | Home            |          3926 |     1853 |
     1963 | 146 | Away            |          3927 |     1863 |
     1964 | 147 | Home            |          3928 |     1872 |
     1964 | 147 | Away            |          3929 |     1873 |
     1965 | 148 | Away            |          3931 |     1869 |
     1965 | 148 | Home            |          3930 |     1870 |
----------+-----+-----------------+---------------+----------+