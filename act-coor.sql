-- Actualización a coordenadas de municipios
-- Informacion extraida de  http://sidih.colombiassh.org/im/divipolaLH/
-- Se cree que es de dominio publico pues en http://www.colombiassh.org/gtmi/wiki/index.php/P%C3%A1gina_Principal el 9.Ene.2013 dice:
-- "Fuentes - las bases de datos (BD) no procesadas que son del dominio público..."

-- La contribución de vtamara@pasosdeJesus.org se cede al Dominio Público de acuerdo a legislación Colombiana. 2013. https://www.pasosdejesus.org/dominio_publico_colombia.html 

-- El "DIVIPOLADOR" citado no encontró información de los siguientes municipios, información que se completó con verificaciones personales
-- BOGOTÁ D.C.,BOGOTÁ; ANTIOQUIA,BOLÍVAR; EXTERIOR,BRASIL; CHOCÓ,ALTO BAUDO; CHOCÓ,CANTON DEL SAN PABLO; ANTIOQUIA,CARMEN DE VIBORAL; MAGDALENA,CERRO DE SAN ANTONIO; META,CUBARRAL; ANTIOQUIA,DON MATÍAS; EXTERIOR,ECUADOR; CHOCÓ,EL CARMEN; NARIÑO,ALBAN; NARIÑO,EL TABLON; GUAINÍA,GUAVIARE (BARRANCO MINAS); ANTIOQUIA,ITAGÜÍ; CÓRDOBA,LA APARTADA (LA FRONTERA); META,LA URIBE; CESAR,MANAURE BALCÓN DEL CESAR; TOLIMA,MARIQUITA; AMAZONAS,MIRITI-PARANÁ; GUAINÍA,MORICHAL (MORICHAL NUEVO); EXTERIOR,PANAMA; EXTERIOR,PERU; ARCHIPIÉLAGO DE SAN ANDRÉS,PROVIDENCIA Y SANTA CATALINA; AMAZONAS,PTO SANTANDER; CUNDINAMARCA,RAFAEL REYES (APULO); ARCHIPIÉLAGO DE SAN ANDRÉS,PROVIDENCIA Y SANTA CATALINA; ANTIOQUIA,SAN ANDRÉS; CÓRDOBA,SAN ANDRÉS SOTAVENTO; CUNDINAMARCA,SAN ANTONIO DE TEQUENDAMA; VICHADA,SAN JOSÉ DE OCUNE; ANTIOQUIA,SAN PEDRO; NARIÑO,SANTA BARBARA (ISCUANDE); ANTIOQUIA,SANTUARIO; SUCRE,SINCÉ; SUCRE,TOLU; SUCRE,TOLUVIEJO; NARIÑO,TUMACO; CUNDINAMARCA,UBATE; CUNDINAMARCA,VENECIA (OSPINA PEREZ); EXTERIOR,VENEZUELA

-- El DIVIPOLAR no dio coordenadas de los siguientes municipios, tambien completados con verificaciones personales
-- MAGDALENA,ZONA BANANERA; GUAINIA,CACAHUAL; AMAZONAS,EL ENCANTO; SANTANDER,GIRÓN; ATLÁNTICO,JUAN DE ACOSTA; AMAZONAS,LA CHORRERA; VALLE DEL CAUCA,LA CUMBRE; GUAINÍA,LA GUADALUPE; AMAZONAS,LA PEDRERA; AMAZONAS,LA VICTORIA; GUAVIARE,MAPIRIPANA; CHOCO,MEDIO ATRATO; CHOCO,MEDIO BAUDO; VAUPES,PACOA; GUAINIA,PANA PANA; VAUPES,PAPUNAUA; BOYÁCA,PAYA; MAGDALENA,PEDRAZA; MAGDALENA,PIJIÑO DEL CARMEN; AMAZONAS,PUERTO ALEGRÍA; AMAZONAS,PUERTO ARICA; GUANÍA,PUERTO COLOMBIA; GUANÍA,SAN FELIPE; CAQUETA,SOLANO; AMAZONAS, TARAPACÁ; VAUPES,YAVARATÉ; CUNDINAMARCA,ZIPACÓN;

-- El DIVIPOLAR dio coordenadas con punto decimal mal ubicado para los siguientes municipios que se arreglaron reubicando el punto decimal
-- 76,41,ANSERMANUEVO; 27,150,CARMEN DEL DARIÉN; 68,235,EL CARMEN DE CHUCURÍ; 20,238,EL COPEY; 18,256,EL PAUJÍL; 54,261,EL ZULIA; 27,450,MEDIO SAN JUAN; 18,479,MORELIA; 86,568,PUERTO ASÍS; 18,592,PUERTO RICO; 47,660,SABANAS DE SAN ANGEL; 23,682,SAN JOSÉ DE URÉ; 47,720,SANTA BARBARA DE PINTO; 18,785,SOLITA; 25,781,SUTATAUSA; 27,810,UNIÓN PANAMERICANA; 86,885,VILLAGARZÓN; 50,1,VILLAVICENCIO; 47,960,ZAPAYÁN; 20,45,BECERRI; 27,150,CARMEN DEL DARIÉN; 18,256,EL PAUJÍL; 47,660,SABANAS DE SAN ANGEL; 23,682,SAN JOSÉ DE URÉ; 25,781,SUTATAUSA; 27,810,UNIÓN PANAMERICANA; 86,885,VILLAGARZÓN; 76,892,YUMBO; 47,960,ZAPAYÁN;


-- Las coordenadas de departamentos son promedio de coordenada de los municipios excepto cuando el promedio está fuera del departamento (BOLÍVAR, SAN ANDRES y RISARALDA)


UPDATE departamento SET latitud='-1.8188796', longitud='-71.3423095454545' WHERE id='91'; -- AMAZONAS
UPDATE departamento SET latitud='6.55021133387096', longitud='-75.554003442742' WHERE id='5'; -- ANTIOQUIA
UPDATE departamento SET latitud='6.66059525714286', longitud='-71.2608333428572' WHERE id='81'; -- ARAUCA
UPDATE departamento SET latitud='12.546186', longitud='-81.719913' WHERE id='88'; -- ARCHIPIÉLAGO DE SAN ANDRÉS, PROVIDENCIA Y SANTA CATALINA
UPDATE departamento SET latitud='10.7079512608696', longitud='-74.9011456478261' WHERE id='8'; -- ATLÁNTICO
UPDATE departamento SET latitud='4.555206', longitud='-74.098663' WHERE id='11'; -- BOGOTÁ D.C.
UPDATE departamento SET latitud='9.86604', longitud='-75.130692' WHERE id='13'; -- BOLÍVAR
UPDATE departamento SET latitud='5.70705419918699', longitud='-73.1773897048781' WHERE id='15'; -- BOYACÁ
UPDATE departamento SET latitud='5.27632715555556', longitud='-75.480895062963' WHERE id='17'; -- CALDAS
UPDATE departamento SET latitud='1.42114465625', longitud='-75.43703043125' WHERE id='18'; -- CAQUETÁ
UPDATE departamento SET latitud='5.4055409368421', longitud='-72.2273099368421' WHERE id='85'; -- CASANARE
UPDATE departamento SET latitud='2.72219576904762', longitud='-76.5414550309524' WHERE id='19'; -- CAUCA
UPDATE departamento SET latitud='9.282755556', longitud='-73.521614668' WHERE id='20'; -- CESAR
UPDATE departamento SET latitud='5.77861863', longitud='-76.8400002833333' WHERE id='27'; -- CHOCÓ
UPDATE departamento SET latitud='4.86646071896552', longitud='-74.1931720534483' WHERE id='25'; -- CUNDINAMARCA
UPDATE departamento SET latitud='8.59571969666666', longitud='-75.6912439333333' WHERE id='23'; -- CÓRDOBA
UPDATE departamento SET latitud='2.73215331111111', longitud='-68.2157238777778' WHERE id='94'; -- GUAINÍA
UPDATE departamento SET latitud='2.04881945', longitud='-72.468611125' WHERE id='95'; -- GUAVIARE
UPDATE departamento SET latitud='2.45617117027027', longitud='-75.7129879918919' WHERE id='41'; -- HUILA
UPDATE departamento SET latitud='11.0673518466667', longitud='-72.7891111066667' WHERE id='44'; -- LA GUAJIRA
UPDATE departamento SET latitud='10.1753674833333', longitud='-74.3720305233333' WHERE id='47'; -- MAGDALENA
UPDATE departamento SET latitud='3.64759304482759', longitud='-73.4262129689655' WHERE id='50'; -- META
UPDATE departamento SET latitud='1.37132189020843', longitud='-77.5449470993627' WHERE id='52'; -- NARIÑO
UPDATE departamento SET latitud='7.9174791675', longitud='-72.76738194' WHERE id='54'; -- NORTE DE SANTANDER
UPDATE departamento SET latitud='0.796213669230769', longitud='-76.6284017153846' WHERE id='86'; -- PUTUMAYO
UPDATE departamento SET latitud='4.49465278333333', longitud='-75.6835879583333' WHERE id='63'; -- QUINDIO
UPDATE departamento SET latitud='5.15147', longitud='-75.918274' WHERE id='66'; -- RISARALDA
UPDATE departamento SET latitud='6.59830477816092', longitud='-73.2261531689655' WHERE id='68'; -- SANTANDER
UPDATE departamento SET latitud='9.20350812307692', longitud='-75.2463821884615' WHERE id='70'; -- SUCRE
UPDATE departamento SET latitud='4.33888798723404', longitud='-75.0640008957447' WHERE id='73'; -- TOLIMA
UPDATE departamento SET latitud='4.11343548272846', longitud='-76.2431594209944' WHERE id='76'; -- VALLE DEL CAUCA
UPDATE departamento SET latitud='0.83483815', longitud='-70.2450135' WHERE id='97'; -- VAUPÉS
UPDATE departamento SET latitud='5.09897964', longitud='-69.77576368' WHERE id='99'; -- VICHADA

UPDATE municipio SET latitud = '5.75', longitud = '-75.4166667' WHERE id_departamento = '5' AND id = '2'; -- ABEJORRAL
UPDATE municipio SET latitud = '6.6666667', longitud = '-76.0833333' WHERE id_departamento = '5' AND id = '4'; -- ABRIAQUÍ
UPDATE municipio SET latitud = '3.9166667', longitud = '-73.8333333' WHERE id_departamento = '50' AND id = '6'; -- ACACÍAS
UPDATE municipio SET latitud = '8.3333333', longitud = '-77.1666667' WHERE id_departamento = '27' AND id = '6'; -- ACANDÍ
UPDATE municipio SET latitud = '1.75', longitud = '-75.9166667' WHERE id_departamento = '41' AND id = '6'; -- ACEVEDO
UPDATE municipio SET latitud = '8.25', longitud = '-74.5' WHERE id_departamento = '13' AND id = '6'; -- ACHÍ
UPDATE municipio SET latitud = '2.3333333', longitud = '-75.75' WHERE id_departamento = '41' AND id = '13'; -- AGRADO
UPDATE municipio SET latitud = '4.4166667', longitud = '-74.6666667' WHERE id_departamento = '25' AND id = '1'; -- AGUA DE DIOS
UPDATE municipio SET latitud = '8.3333333', longitud = '-73.5833333' WHERE id_departamento = '20' AND id = '11'; -- AGUACHICA
UPDATE municipio SET latitud = '6.25', longitud = '-73.4666667' WHERE id_departamento = '68' AND id = '13'; -- AGUADA
UPDATE municipio SET latitud = '5.6333333', longitud = '-75.4166667' WHERE id_departamento = '17' AND id = '13'; -- AGUADAS
UPDATE municipio SET latitud = '5.1730556', longitud = '-72.5547222' WHERE id_departamento = '85' AND id = '10'; -- AGUAZUL
UPDATE municipio SET latitud = '9.9166667', longitud = '-73.25' WHERE id_departamento = '20' AND id = '13'; -- AGUSTÍN CODAZZI
UPDATE municipio SET latitud = '3.25', longitud = '-75.3333333' WHERE id_departamento = '41' AND id = '16'; -- AIPE
UPDATE municipio SET latitud='1.0963', longitud='-77.5686' WHERE id='19' AND id_departamento='52'; --ALBAN, NARIÑO
UPDATE municipio SET latitud = '5.8333333', longitud = '-73.75' WHERE id_departamento = '68' AND id = '20'; -- ALBANIA
UPDATE municipio SET latitud = '11.1597222', longitud = '-72.5855556' WHERE id_departamento = '44' AND id = '35'; -- ALBANIA
UPDATE municipio SET latitud = '1.3316667', longitud = '-75.8822222' WHERE id_departamento = '18' AND id = '29'; -- ALBANIA
UPDATE municipio SET latitud = '4.9166667', longitud = '-74.45' WHERE id_departamento = '25' AND id = '19'; -- ALBÁN
UPDATE municipio SET latitud = '4.6666667', longitud = '-75.75' WHERE id_departamento = '76' AND id = '20'; -- ALCALÁ
UPDATE municipio SET latitud = '0.9166667', longitud = '-77.6833333' WHERE id_departamento = '52' AND id = '22'; -- ALDANA
UPDATE municipio SET latitud = '6.3666667', longitud = '-75.0833333' WHERE id_departamento = '5' AND id = '21'; -- ALEJANDRÍA
UPDATE municipio SET latitud = '10.1869444', longitud = '-74.5752778' WHERE id_departamento = '47' AND id = '30'; -- ALGARROBO
UPDATE municipio SET latitud = '2.5833333', longitud = '-75.25' WHERE id_departamento = '41' AND id = '20'; -- ALGECIRAS
UPDATE municipio SET latitud = '1.9166667', longitud = '-76.8333333' WHERE id_departamento = '19' AND id = '22'; -- ALMAGUER
UPDATE municipio SET latitud = '4.9166667', longitud = '-73.3333333' WHERE id_departamento = '15' AND id = '22'; -- ALMEIDA
UPDATE municipio SET latitud = '3.4166667', longitud = '-74.9166667' WHERE id_departamento = '73' AND id = '24'; -- ALPUJARRA
UPDATE municipio SET latitud = '2.1666667', longitud = '-75.8333333' WHERE id_departamento = '41' AND id = '26'; -- ALTAMIRA
UPDATE municipio SET latitud='5.5161', longitud='-76.9747' WHERE id='25' AND id_departamento='27'; --ALTO BAUDO, CHOCÓ
UPDATE municipio SET latitud = '8.7944444', longitud = '-74.1658333' WHERE id_departamento = '13' AND id = '30'; -- ALTOS DEL ROSARIO
UPDATE municipio SET latitud = '4.5833333', longitud = '-75.0' WHERE id_departamento = '73' AND id = '26'; -- ALVARADO
UPDATE municipio SET latitud = '6.05', longitud = '-75.7' WHERE id_departamento = '5' AND id = '30'; -- AMAGÁ
UPDATE municipio SET latitud = '7.0', longitud = '-74.9166667' WHERE id_departamento = '5' AND id = '31'; -- AMALFI
UPDATE municipio SET latitud = '4.8333333', longitud = '-74.8333333' WHERE id_departamento = '73' AND id = '30'; -- AMBALEMA
UPDATE municipio SET latitud = '4.6166667', longitud = '-74.55' WHERE id_departamento = '25' AND id = '35'; -- ANAPOIMA
UPDATE municipio SET latitud = '1.25', longitud = '-77.5' WHERE id_departamento = '52' AND id = '36'; -- ANCUYÁ
UPDATE municipio SET latitud = '4.1741667', longitud = '-76.1702778' WHERE id_departamento = '76' AND id = '36'; -- ANDALUCÍA
UPDATE municipio SET latitud = '5.5833333', longitud = '-75.9166667' WHERE id_departamento = '5' AND id = '34'; -- ANDES
UPDATE municipio SET latitud = '6.1333333', longitud = '-75.7' WHERE id_departamento = '5' AND id = '36'; -- ANGELÓPOLIS
UPDATE municipio SET latitud = '6.8666667', longitud = '-75.3333333' WHERE id_departamento = '5' AND id = '38'; -- ANGOSTURA
UPDATE municipio SET latitud = '4.8333333', longitud = '-74.5' WHERE id_departamento = '25' AND id = '40'; -- ANOLAIMA
UPDATE municipio SET latitud = '7.1666667', longitud = '-75.0833333' WHERE id_departamento = '5' AND id = '40'; -- ANORÍ
UPDATE municipio SET latitud = '5.25', longitud = '-75.75' WHERE id_departamento = '17' AND id = '42'; -- ANSERMA
UPDATE municipio SET latitud = '4.7972222', longitud = '-75.995' WHERE id_departamento = '76' AND id = '41'; -- ANSERMANUEVO
UPDATE municipio SET latitud = '4.6666667', longitud = '-75.25' WHERE id_departamento = '73' AND id = '43'; -- ANZOÁTEGUI
UPDATE municipio SET latitud = '6.3333333', longitud = '-75.9166667' WHERE id_departamento = '5' AND id = '44'; -- ANZÁ
UPDATE municipio SET latitud = '7.8855556', longitud = '-76.6347222' WHERE id_departamento = '5' AND id = '45'; -- APARTADÓ
UPDATE municipio SET latitud = '5.1666667', longitud = '-76.0' WHERE id_departamento = '66' AND id = '45'; -- APÍA
UPDATE municipio SET latitud = '5.5197222', longitud = '-72.8875' WHERE id_departamento = '15' AND id = '47'; -- AQUITANIA
UPDATE municipio SET latitud = '10.5833333', longitud = '-74.0' WHERE id_departamento = '47' AND id = '53'; -- ARACATACA
UPDATE municipio SET latitud = '5.3', longitud = '-75.45' WHERE id_departamento = '17' AND id = '50'; -- ARANZAZU
UPDATE municipio SET latitud = '6.8333333', longitud = '-72.9666667' WHERE id_departamento = '68' AND id = '51'; -- ARATOCA
UPDATE municipio SET latitud = '7.0902778', longitud = '-70.7616667' WHERE id_departamento = '81' AND id = '1'; -- ARAUCA
UPDATE municipio SET latitud = '6.8219444', longitud = '-71.3236111' WHERE id_departamento = '81' AND id = '65'; -- ARAUQUITA
UPDATE municipio SET latitud = '4.3', longitud = '-74.5833333' WHERE id_departamento = '25' AND id = '53'; -- ARBELÁEZ
UPDATE municipio SET latitud = '1.5033333', longitud = '-77.0877778' WHERE id_departamento = '52' AND id = '51'; -- ARBOLEDA
UPDATE municipio SET latitud = '7.6666667', longitud = '-72.75' WHERE id_departamento = '54' AND id = '51'; -- ARBOLEDAS
UPDATE municipio SET latitud = '8.5', longitud = '-76.4166667' WHERE id_departamento = '5' AND id = '51'; -- ARBOLETES
UPDATE municipio SET latitud = '5.8', longitud = '-73.3833333' WHERE id_departamento = '15' AND id = '51'; -- ARCABUCO
UPDATE municipio SET latitud = '8.4666667', longitud = '-73.95' WHERE id_departamento = '13' AND id = '42'; -- ARENAL
UPDATE municipio SET latitud = '2.2666667', longitud = '-77.25' WHERE id_departamento = '19' AND id = '50'; -- ARGELIA
UPDATE municipio SET latitud = '4.7', longitud = '-76.1333333' WHERE id_departamento = '76' AND id = '54'; -- ARGELIA
UPDATE municipio SET latitud = '5.7341667', longitud = '-75.1463889' WHERE id_departamento = '5' AND id = '55'; -- ARGELIA
UPDATE municipio SET latitud = '10.25', longitud = '-74.0' WHERE id_departamento = '47' AND id = '58'; -- ARIGUANÍ
UPDATE municipio SET latitud = '6.1666667', longitud = '-75.8333333' WHERE id_departamento = '5' AND id = '59'; -- ARMENIA
UPDATE municipio SET latitud = '4.5338889', longitud = '-75.6811111' WHERE id_departamento = '63' AND id = '1'; -- ARMENIA
UPDATE municipio SET latitud = '5.0833333', longitud = '-74.8333333' WHERE id_departamento = '73' AND id = '55'; -- ARMERO
UPDATE municipio SET latitud = '10.1666667', longitud = '-75.3333333' WHERE id_departamento = '13' AND id = '52'; -- ARJONA
UPDATE municipio SET latitud = '10.2497222', longitud = '-75.0113889' WHERE id_departamento = '13' AND id = '62'; -- ARROYOHONDO
UPDATE municipio SET latitud = '9.5011111', longitud = '-73.9802778' WHERE id_departamento = '20' AND id = '32'; -- ASTREA
UPDATE municipio SET latitud = '3.3333333', longitud = '-75.6666667' WHERE id_departamento = '73' AND id = '67'; -- ATACO
UPDATE municipio SET latitud = '5.5333333', longitud = '-76.6333333' WHERE id_departamento = '27' AND id = '50'; -- ATRATO
UPDATE municipio SET latitud = '8.25', longitud = '-75.25' WHERE id_departamento = '23' AND id = '68'; -- AYAPEL
UPDATE municipio SET latitud = '5.5', longitud = '-76.25' WHERE id_departamento = '27' AND id = '73'; -- BAGADÓ
UPDATE municipio SET latitud = '6.2191667', longitud = '-77.4077778' WHERE id_departamento = '27' AND id = '75'; -- BAHIA SOLANO
UPDATE municipio SET latitud = '5.0', longitud = '-77.083333' WHERE id_departamento = '27' AND id = '77'; -- BAJO BAUDO
UPDATE municipio SET latitud = '2.1', longitud = '-77.2' WHERE id_departamento = '19' AND id = '75'; -- BALBOA
UPDATE municipio SET latitud = '4.9166667', longitud = '-75.95' WHERE id_departamento = '66' AND id = '75'; -- BALBOA
UPDATE municipio SET latitud = '10.8', longitud = '-74.9166667' WHERE id_departamento = '8' AND id = '78'; -- BARANOA
UPDATE municipio SET latitud = '3.1666667', longitud = '-75.0' WHERE id_departamento = '41' AND id = '78'; -- BARAYA
UPDATE municipio SET latitud = '1.5833333', longitud = '-78.0' WHERE id_departamento = '52' AND id = '79'; -- BARBACOAS
UPDATE municipio SET latitud = '6.0', longitud = '-73.55' WHERE id_departamento = '68' AND id = '77'; -- BARBOSA
UPDATE municipio SET latitud = '6.45', longitud = '-75.3333333' WHERE id_departamento = '5' AND id = '79'; -- BARBOSA
UPDATE municipio SET latitud = '6.75', longitud = '-73.1666667' WHERE id_departamento = '68' AND id = '79'; -- BARICHARA
UPDATE municipio SET latitud = '4.5755556', longitud = '-72.9611111' WHERE id_departamento = '50' AND id = '110'; -- BARRANCA DE UPÍA
UPDATE municipio SET latitud = '7.0652778', longitud = '-73.8547222' WHERE id_departamento = '68' AND id = '81'; -- BARRANCABERMEJA
UPDATE municipio SET latitud = '11.0', longitud = '-72.75' WHERE id_departamento = '44' AND id = '78'; -- BARRANCAS
UPDATE municipio SET latitud = '8.8333333', longitud = '-74.1666667' WHERE id_departamento = '13' AND id = '74'; -- BARRANCO DE LOBA
UPDATE municipio SET latitud = '10.9638889', longitud = '-74.7963889' WHERE id_departamento = '8' AND id = '1'; -- BARRANQUILLA
UPDATE municipio SET latitud = '9.705', longitud = '-73.2847222' WHERE id_departamento = '20' AND id = '45'; -- BECERRIL
UPDATE municipio SET latitud = '5.0', longitud = '-75.8333333' WHERE id_departamento = '17' AND id = '88'; -- BELALCÁZAR
UPDATE municipio SET latitud = '6.3388889', longitud = '-75.5622222' WHERE id_departamento = '5' AND id = '88'; -- BELLO
UPDATE municipio SET latitud = '6.6666667', longitud = '-75.6666667' WHERE id_departamento = '5' AND id = '86'; -- BELMIRA
UPDATE municipio SET latitud = '4.8', longitud = '-74.75' WHERE id_departamento = '25' AND id = '86'; -- BELTRÁN
UPDATE municipio SET latitud = '6.0833333', longitud = '-72.9166667' WHERE id_departamento = '15' AND id = '87'; -- BELÉN
UPDATE municipio SET latitud = '1.5975', longitud = '-77.0175' WHERE id_departamento = '52' AND id = '83'; -- BELÉN
UPDATE municipio SET latitud = '1.4166667', longitud = '-75.9166667' WHERE id_departamento = '18' AND id = '94'; -- BELÉN DE LOS ANDAQUÍES
UPDATE municipio SET latitud = '5.25', longitud = '-75.9166667' WHERE id_departamento = '66' AND id = '88'; -- BELÉN DE UMBRÍA
UPDATE municipio SET latitud = '5.3333333', longitud = '-73.0' WHERE id_departamento = '15' AND id = '90'; -- BERBEO
UPDATE municipio SET latitud = '5.75', longitud = '-75.9666667' WHERE id_departamento = '5' AND id = '91'; -- BETANIA
UPDATE municipio SET latitud = '6.2', longitud = '-75.9166667' WHERE id_departamento = '5' AND id = '93'; -- BETULIA
UPDATE municipio SET latitud = '7.0833333', longitud = '-73.3333333' WHERE id_departamento = '68' AND id = '92'; -- BETULIA
UPDATE municipio SET latitud = '6.0', longitud = '-72.8333333' WHERE id_departamento = '15' AND id = '92'; -- BETÉITIVA
UPDATE municipio SET latitud = '4.9166667', longitud = '-74.55' WHERE id_departamento = '25' AND id = '95'; -- BITUIMA
UPDATE municipio SET latitud = '6.4166667', longitud = '-72.5833333' WHERE id_departamento = '15' AND id = '97'; -- BOAVITA
UPDATE municipio SET latitud = '7.6666667', longitud = '-72.5833333' WHERE id_departamento = '54' AND id = '99'; -- BOCHALEMA
UPDATE municipio SET latitud = '4.555206', longitud = '-74.098663' WHERE id_departamento = '11' AND id = '1'; -- BOGOTÁ D.C.,BOGOTÁ,
UPDATE municipio SET latitud = '4.75', longitud = '-74.3333333' WHERE id_departamento = '25' AND id = '99'; -- BOJACÁ
UPDATE municipio SET latitud = '6.5236111', longitud = '-76.974444' WHERE id_departamento = '27' AND id = '99'; -- BOJAYÁ
UPDATE municipio SET latitud = '6.25', longitud = '-74.0' WHERE id_departamento = '68' AND id = '101'; -- BOLÍVAR
UPDATE municipio SET latitud = '1.8333333', longitud = '-76.9166667' WHERE id_departamento = '19' AND id = '100'; -- BOLÍVAR
UPDATE municipio SET latitud = '5.843155', longitud = '-76.021099' WHERE id_departamento = '5' AND id = '101'; -- ANTIOQUIA,BOLÍVAR
UPDATE municipio SET latitud = '4.3333333', longitud = '-76.3333333' WHERE id_departamento = '76' AND id = '100'; -- BOLÍVAR
UPDATE municipio SET latitud = '9.9761111', longitud = '-73.8902778' WHERE id_departamento = '20' AND id = '60'; -- BOSCONIA
UPDATE municipio SET latitud = '5.5', longitud = '-72.5' WHERE id_departamento = '15' AND id = '104'; -- BOYACÁ
UPDATE municipio SET latitud='-8.4666145', longitud='-51.3333976' WHERE id='5' AND id_departamento='0'; --BRASIL, EXTERIOR
UPDATE municipio SET latitud = '5.75', longitud = '-73.8333333' WHERE id_departamento = '15' AND id = '106'; -- BRICEÑO
UPDATE municipio SET latitud = '7.1122222', longitud = '-75.5541667' WHERE id_departamento = '5' AND id = '107'; -- BRICEÑO
UPDATE municipio SET latitud = '7.1297222', longitud = '-73.1258333' WHERE id_departamento = '68' AND id = '1'; -- BUCARAMANGA
UPDATE municipio SET latitud = '8.0833333', longitud = '-73.0' WHERE id_departamento = '54' AND id = '109'; -- BUCARASICA
UPDATE municipio SET latitud = '3.8933333', longitud = '-77.0697222' WHERE id_departamento = '76' AND id = '109'; -- BUENAVENTURA
UPDATE municipio SET latitud = '5.5', longitud = '-73.9666667' WHERE id_departamento = '15' AND id = '109'; -- BUENAVISTA
UPDATE municipio SET latitud = '8.7575', longitud = '-75.89' WHERE id_departamento = '23' AND id = '79'; -- BUENAVISTA
UPDATE municipio SET latitud = '9.3222222', longitud = '-74.9772222' WHERE id_departamento = '70' AND id = '110'; -- BUENAVISTA
UPDATE municipio SET latitud = '4.3622222', longitud = '-75.7394444' WHERE id_departamento = '63' AND id = '111'; -- BUENAVISTA
UPDATE municipio SET latitud = '2.9166667', longitud = '-76.6666667' WHERE id_departamento = '19' AND id = '110'; -- BUENOS AIRES
UPDATE municipio SET latitud = '1.25', longitud = '-77.0833333' WHERE id_departamento = '52' AND id = '110'; -- BUESACO
UPDATE municipio SET latitud = '4.2094444', longitud = '-76.1602778' WHERE id_departamento = '76' AND id = '113'; -- BUGALAGRANDE
UPDATE municipio SET latitud = '6.75', longitud = '-75.9166667' WHERE id_departamento = '5' AND id = '113'; -- BURITICÁ
UPDATE municipio SET latitud = '5.9166667', longitud = '-72.85' WHERE id_departamento = '15' AND id = '114'; -- BUSBANZÁ
UPDATE municipio SET latitud = '3.9780556', longitud = '-74.4858333' WHERE id_departamento = '25' AND id = '120'; -- CABRERA
UPDATE municipio SET latitud = '6.6333333', longitud = '-73.2' WHERE id_departamento = '68' AND id = '121'; -- CABRERA
UPDATE municipio SET latitud = '4.2902778', longitud = '-72.7905556' WHERE id_departamento = '50' AND id = '124'; -- CABUYARO
UPDATE municipio SET latitud='3.5261', longitud='-67.4126' WHERE id='886' AND id_departamento='94'; --CACAHUAL, GUAINÍA
UPDATE municipio SET latitud = '5.2666667', longitud = '-74.5666667' WHERE id_departamento = '25' AND id = '123'; -- CACHIPAY
UPDATE municipio SET latitud = '7.75', longitud = '-73.1666667' WHERE id_departamento = '54' AND id = '128'; -- CACHIRÁ
UPDATE municipio SET latitud = '6.45', longitud = '-75.9666667' WHERE id_departamento = '5' AND id = '125'; -- CAICEDO
UPDATE municipio SET latitud = '4.33365917459535', longitud = '-75.8342456817627' WHERE id_departamento = '76' AND id = '122'; -- CAICEDONIA
UPDATE municipio SET latitud = '8.8333333', longitud = '-75.1666667' WHERE id_departamento = '70' AND id = '124'; -- CAIMITO
UPDATE municipio SET latitud = '4.4166667', longitud = '-75.5' WHERE id_departamento = '73' AND id = '124'; -- CAJAMARCA
UPDATE municipio SET latitud = '2.6666667', longitud = '-76.6666667' WHERE id_departamento = '19' AND id = '130'; -- CAJIBÍO
UPDATE municipio SET latitud = '5.0', longitud = '-74.05' WHERE id_departamento = '25' AND id = '126'; -- CAJICÁ
UPDATE municipio SET latitud = '1.9597222', longitud = '-72.6538889' WHERE id_departamento = '95' AND id = '15'; -- CALAMAR
UPDATE municipio SET latitud = '10.25', longitud = '-75.0' WHERE id_departamento = '13' AND id = '140'; -- CALAMAR
UPDATE municipio SET latitud = '4.5325', longitud = '-75.6436111' WHERE id_departamento = '63' AND id = '130'; -- CALARCÁ
UPDATE municipio SET latitud = '6.0833333', longitud = '-75.6333333' WHERE id_departamento = '5' AND id = '129'; -- CALDAS
UPDATE municipio SET latitud = '5.55', longitud = '-73.8833333' WHERE id_departamento = '15' AND id = '131'; -- CALDAS
UPDATE municipio SET latitud = '2.8333333', longitud = '-76.4166667' WHERE id_departamento = '19' AND id = '137'; -- CALDONO
UPDATE municipio SET latitud = '3.4372222', longitud = '-76.5225' WHERE id_departamento = '76' AND id = '1'; -- CALI
UPDATE municipio SET latitud = '7.4166667', longitud = '-72.85' WHERE id_departamento = '68' AND id = '132'; -- CALIFORNIA
UPDATE municipio SET latitud = '3.9166667', longitud = '-76.6666667' WHERE id_departamento = '76' AND id = '126'; -- CALIMA
UPDATE municipio SET latitud = '3.0833333', longitud = '-76.3333333' WHERE id_departamento = '19' AND id = '142'; -- CALOTO
UPDATE municipio SET latitud = '7.0833333', longitud = '-75.25' WHERE id_departamento = '5' AND id = '134'; -- CAMPAMENTO
UPDATE municipio SET latitud = '10.4166667', longitud = '-74.9166667' WHERE id_departamento = '8' AND id = '137'; -- CAMPO DE LA CRUZ
UPDATE municipio SET latitud = '2.6666667', longitud = '-75.3333333' WHERE id_departamento = '41' AND id = '132'; -- CAMPOALEGRE
UPDATE municipio SET latitud = '5.0833333', longitud = '-73.0' WHERE id_departamento = '15' AND id = '135'; -- CAMPOHERMOSO
UPDATE municipio SET latitud = '8.6761111', longitud = '-76.2041667' WHERE id_departamento = '23' AND id = '90'; -- CANALETE
UPDATE municipio SET latitud = '10.5', longitud = '-74.8333333' WHERE id_departamento = '8' AND id = '141'; -- CANDELARIA
UPDATE municipio SET latitud = '3.4130556', longitud = '-76.3511111' WHERE id_departamento = '76' AND id = '130'; -- CANDELARIA
UPDATE municipio SET latitud = '7.3791667', longitud = '-73.9180556' WHERE id_departamento = '13' AND id = '160'; -- CANTAGALLO
UPDATE municipio SET latitud='5.3776', longitud='-76.76199' WHERE id='135' AND id_departamento='27'; --CANTON DEL SAN PABLO, CHOCÓ
UPDATE municipio SET latitud = '5.4166667', longitud = '-74.5833333' WHERE id_departamento = '25' AND id = '148'; -- CAPARRAPÍ
UPDATE municipio SET latitud = '6.6', longitud = '-72.6333333' WHERE id_departamento = '68' AND id = '147'; -- CAPITANEJO
UPDATE municipio SET latitud = '6.4119444', longitud = '-74.7605556' WHERE id_departamento = '5' AND id = '142'; -- CARACOLÍ
UPDATE municipio SET latitud = '5.5833333', longitud = '-75.5833333' WHERE id_departamento = '5' AND id = '145'; -- CARAMANTA
UPDATE municipio SET latitud = '6.8333333', longitud = '-72.5' WHERE id_departamento = '68' AND id = '152'; -- CARCASÍ
UPDATE municipio SET latitud = '7.7663889', longitud = '-76.6611111' WHERE id_departamento = '5' AND id = '147'; -- CAREPA
UPDATE municipio SET latitud = '4.15', longitud = '-74.7333333' WHERE id_departamento = '73' AND id = '148'; -- CARMEN DE APICALÁ
UPDATE municipio SET latitud = '5.3502778', longitud = '-73.9052778' WHERE id_departamento = '25' AND id = '154'; -- CARMEN DE CARUPA
UPDATE municipio SET latitud='6.0838', longitud='-75.336' WHERE id='148' AND id_departamento='5'; --CARMEN DE VIBORAL, ANTIOQUIA
UPDATE municipio SET latitud = '7.158', longitud = '-76.971' WHERE id_departamento = '27' AND id = '150'; -- CARMEN DEL DARIÉN
UPDATE municipio SET latitud = '6.8', longitud = '-75.25' WHERE id_departamento = '5' AND id = '150'; -- CAROLINA
UPDATE municipio SET latitud = '10.3997222', longitud = '-75.5144444' WHERE id_departamento = '13' AND id = '1'; -- CARTAGENA
UPDATE municipio SET latitud = '1.35', longitud = '-74.84' WHERE id_departamento = '18' AND id = '150'; -- CARTAGENA DEL CHAIRÁ
UPDATE municipio SET latitud = '4.7463889', longitud = '-75.9116667' WHERE id_departamento = '76' AND id = '147'; -- CARTAGO
UPDATE municipio SET latitud = '0.9166667', longitud = '-70.7333333' WHERE id_departamento = '97' AND id = '161'; -- CARURÚ
UPDATE municipio SET latitud = '5.0833333', longitud = '-75.1666667' WHERE id_departamento = '73' AND id = '152'; -- CASABIANCA
UPDATE municipio SET latitud = '3.8', longitud = '-73.5833333' WHERE id_departamento = '50' AND id = '150'; -- CASTILLA LA NUEVA
UPDATE municipio SET latitud = '7.9166667', longitud = '-75.0' WHERE id_departamento = '5' AND id = '154'; -- CAUCASIA
UPDATE municipio SET latitud = '6.75', longitud = '-76.0' WHERE id_departamento = '5' AND id = '138'; -- CAÑASGORDAS
UPDATE municipio SET latitud = '6.8333333', longitud = '-72.9166667' WHERE id_departamento = '68' AND id = '160'; -- CEPITÁ
UPDATE municipio SET latitud = '8.9166667', longitud = '-76.0' WHERE id_departamento = '23' AND id = '162'; -- CERETÉ
UPDATE municipio SET latitud = '6.0', longitud = '-72.9166667' WHERE id_departamento = '15' AND id = '162'; -- CERINZA
UPDATE municipio SET latitud = '7.0', longitud = '-72.5833333' WHERE id_departamento = '68' AND id = '162'; -- CERRITO
UPDATE municipio SET latitud='10.2912', longitud='-74.8071' WHERE id='161' AND id_departamento='47'; --CERRO DE SAN ANTONIO, MAGDALENA
UPDATE municipio SET latitud = '1.35943053925138', longitud = '-77.2836685180664' WHERE id_departamento = '52' AND id = '240'; -- CHACHAGÜÍ
UPDATE municipio SET latitud = '5.0', longitud = '-74.65' WHERE id_departamento = '25' AND id = '168'; -- CHAGUANÍ
UPDATE municipio SET latitud = '9.5491667', longitud = '-75.3161111' WHERE id_departamento = '70' AND id = '230'; -- CHALÁN
UPDATE municipio SET latitud = '3.75', longitud = '-75.5833333' WHERE id_departamento = '73' AND id = '168'; -- CHAPARRAL
UPDATE municipio SET latitud = '6.25', longitud = '-73.0833333' WHERE id_departamento = '68' AND id = '167'; -- CHARALÁ
UPDATE municipio SET latitud = '7.3', longitud = '-72.9666667' WHERE id_departamento = '68' AND id = '169'; -- CHARTA
UPDATE municipio SET latitud = '7.6666667', longitud = '-76.6666667' WHERE id_departamento = '5' AND id = '172'; -- CHIGORODÓ
UPDATE municipio SET latitud = '6.4166667', longitud = '-73.3333333' WHERE id_departamento = '68' AND id = '176'; -- CHIMA
UPDATE municipio SET latitud = '9.2577778', longitud = '-73.8177778' WHERE id_departamento = '20' AND id = '175'; -- CHIMICHAGUA
UPDATE municipio SET latitud = '9.0833333', longitud = '-75.6333333' WHERE id_departamento = '23' AND id = '168'; -- CHIMÁ
UPDATE municipio SET latitud = '5.25', longitud = '-73.3333333' WHERE id_departamento = '15' AND id = '172'; -- CHINAVITA
UPDATE municipio SET latitud = '5.0', longitud = '-75.6666667' WHERE id_departamento = '17' AND id = '174'; -- CHINCHINÁ
UPDATE municipio SET latitud = '7.75', longitud = '-72.55' WHERE id_departamento = '54' AND id = '172'; -- CHINÁCOTA
UPDATE municipio SET latitud = '9.0833333', longitud = '-75.3333333' WHERE id_departamento = '23' AND id = '182'; -- CHINÚ
UPDATE municipio SET latitud = '4.5', longitud = '-74.0833333' WHERE id_departamento = '25' AND id = '178'; -- CHIPAQUE
UPDATE municipio SET latitud = '6.1333333', longitud = '-73.55' WHERE id_departamento = '68' AND id = '179'; -- CHIPATÁ
UPDATE municipio SET latitud = '5.6333333', longitud = '-73.75' WHERE id_departamento = '15' AND id = '176'; -- CHIQUINQUIRÁ
UPDATE municipio SET latitud = '9.5', longitud = '-73.4166667' WHERE id_departamento = '20' AND id = '178'; -- CHIRIGUANÁ
UPDATE municipio SET latitud = '6.75', longitud = '-72.3333333' WHERE id_departamento = '15' AND id = '180'; -- CHISCAS
UPDATE municipio SET latitud = '6.1666667', longitud = '-72.4166667' WHERE id_departamento = '15' AND id = '183'; -- CHITA
UPDATE municipio SET latitud = '7.1666667', longitud = '-72.5833333' WHERE id_departamento = '54' AND id = '174'; -- CHITAGÁ
UPDATE municipio SET latitud = '6.0833333', longitud = '-73.3666667' WHERE id_departamento = '15' AND id = '185'; -- CHITARAQUE
UPDATE municipio SET latitud = '5.5333333', longitud = '-73.2666667' WHERE id_departamento = '15' AND id = '187'; -- CHIVATÁ
UPDATE municipio SET latitud = '10.0280556', longitud = '-74.6436111' WHERE id_departamento = '47' AND id = '170'; -- CHIVOLO
UPDATE municipio SET latitud = '4.8855556', longitud = '-73.3688889' WHERE id_departamento = '15' AND id = '236'; -- CHIVOR
UPDATE municipio SET latitud = '4.5833333', longitud = '-73.9166667' WHERE id_departamento = '25' AND id = '181'; -- CHOACHÍ
UPDATE municipio SET latitud = '5.1666667', longitud = '-73.6666667' WHERE id_departamento = '25' AND id = '183'; -- CHOCONTÁ
UPDATE municipio SET latitud = '5.0', longitud = '-72.75' WHERE id_departamento = '85' AND id = '15'; -- CHÁMEZA
UPDATE municipio SET latitud = '4.9166667', longitud = '-74.05' WHERE id_departamento = '25' AND id = '175'; -- CHÍA
UPDATE municipio SET latitud = '5.6083333', longitud = '-73.4886111' WHERE id_departamento = '15' AND id = '232'; -- CHÍQUIZA
UPDATE municipio SET latitud = '9.4166667', longitud = '-74.7333333' WHERE id_departamento = '13' AND id = '188'; -- CICUCO
UPDATE municipio SET latitud = '6.3161111', longitud = '-73.9505556' WHERE id_departamento = '68' AND id = '190'; -- CIMITARRA
UPDATE municipio SET latitud = '4.6166667', longitud = '-75.6333333' WHERE id_departamento = '63' AND id = '190'; -- CIRCASIA
UPDATE municipio SET latitud = '6.5833333', longitud = '-75.0833333' WHERE id_departamento = '5' AND id = '190'; -- CISNEROS
UPDATE municipio SET latitud = '10.8333333', longitud = '-74.0833333' WHERE id_departamento = '47' AND id = '189'; -- CIÉNAGA
UPDATE municipio SET latitud = '8.8333333', longitud = '-75.6666667' WHERE id_departamento = '23' AND id = '189'; -- CIÉNAGA DE ORO
UPDATE municipio SET latitud = '5.4666667', longitud = '-73.25' WHERE id_departamento = '15' AND id = '189'; -- CIÉNEGA
UPDATE municipio SET latitud = '10.5891667', longitud = '-75.3341667' WHERE id_departamento = '13' AND id = '222'; -- CLEMENCIA
UPDATE municipio SET latitud = '6.0', longitud = '-75.0' WHERE id_departamento = '5' AND id = '197'; -- COCORNÁ
UPDATE municipio SET latitud = '4.3333333', longitud = '-74.9166667' WHERE id_departamento = '73' AND id = '200'; -- COELLO
UPDATE municipio SET latitud = '5.15', longitud = '-73.9666667' WHERE id_departamento = '25' AND id = '200'; -- COGUA
UPDATE municipio SET latitud = '2.5', longitud = '-75.75' WHERE id_departamento = '41' AND id = '206'; -- COLOMBIA
UPDATE municipio SET latitud = '1.5833333', longitud = '-77.0' WHERE id_departamento = '52' AND id = '203'; -- COLON
UPDATE municipio SET latitud = '9.4966667', longitud = '-75.3555556' WHERE id_departamento = '70' AND id = '204'; -- COLOSO
UPDATE municipio SET latitud = '1.1936111', longitud = '-76.9769444' WHERE id_departamento = '86' AND id = '219'; -- COLÓN
UPDATE municipio SET latitud = '6.9166667', longitud = '-72.5' WHERE id_departamento = '68' AND id = '207'; -- CONCEPCIÓN
UPDATE municipio SET latitud = '6.4166667', longitud = '-75.25' WHERE id_departamento = '5' AND id = '206'; -- CONCEPCIÓN
UPDATE municipio SET latitud = '9.8402778', longitud = '-74.4447222' WHERE id_departamento = '47' AND id = '205'; -- CONCORDIA
UPDATE municipio SET latitud = '6.0833333', longitud = '-75.9166667' WHERE id_departamento = '5' AND id = '209'; -- CONCORDIA
UPDATE municipio SET latitud = '5.1666667', longitud = '-76.5' WHERE id_departamento = '27' AND id = '205'; -- CONDOTO
UPDATE municipio SET latitud = '6.4166667', longitud = '-73.1666667' WHERE id_departamento = '68' AND id = '209'; -- CONFINES
UPDATE municipio SET latitud = '1.20840649727186', longitud = '-77.4632263183594' WHERE id_departamento = '52' AND id = '207'; -- CONSACÁ
UPDATE municipio SET latitud = '0.9166667', longitud = '-77.5333333' WHERE id_departamento = '52' AND id = '210'; -- CONTADERO
UPDATE municipio SET latitud = '6.3333333', longitud = '-73.4166667' WHERE id_departamento = '68' AND id = '211'; -- CONTRATACIÓN
UPDATE municipio SET latitud = '8.8333333', longitud = '-73.2' WHERE id_departamento = '54' AND id = '206'; -- CONVENCIÓN
UPDATE municipio SET latitud = '6.3333333', longitud = '-75.5' WHERE id_departamento = '5' AND id = '212'; -- COPACABANA
UPDATE municipio SET latitud = '5.4333333', longitud = '-74.0333333' WHERE id_departamento = '15' AND id = '212'; -- COPER
UPDATE municipio SET latitud = '3.1666667', longitud = '-76.2' WHERE id_departamento = '19' AND id = '212'; -- CORINTO
UPDATE municipio SET latitud = '6.3333333', longitud = '-73.0' WHERE id_departamento = '68' AND id = '217'; -- COROMORO
UPDATE municipio SET latitud = '9.3333333', longitud = '-75.25' WHERE id_departamento = '70' AND id = '215'; -- COROZAL
UPDATE municipio SET latitud = '5.8666667', longitud = '-72.8333333' WHERE id_departamento = '15' AND id = '215'; -- CORRALES
UPDATE municipio SET latitud = '4.8333333', longitud = '-74.1333333' WHERE id_departamento = '25' AND id = '214'; -- COTA
UPDATE municipio SET latitud = '9.05', longitud = '-75.8' WHERE id_departamento = '23' AND id = '300'; -- COTORRA
UPDATE municipio SET latitud = '6.5833333', longitud = '-72.7' WHERE id_departamento = '15' AND id = '218'; -- COVARACHÍA
UPDATE municipio SET latitud = '9.4166667', longitud = '-75.7' WHERE id_departamento = '70' AND id = '221'; -- COVEÑAS
UPDATE municipio SET latitud = '3.8333333', longitud = '-75.0833333' WHERE id_departamento = '73' AND id = '217'; -- COYAIMA
UPDATE municipio SET latitud = '6.3030556', longitud = '-70.2016667' WHERE id_departamento = '81' AND id = '220'; -- CRAVO NORTE
UPDATE municipio SET latitud = '0.8658333', longitud = '-77.7294444' WHERE id_departamento = '52' AND id = '224'; -- CUASPUD
UPDATE municipio SET latitud='3.7926072', longitud='-73.8392945' WHERE id='223' AND id_departamento='50'; --CUBARRAL, META
UPDATE municipio SET latitud = '7.0411111', longitud = '-72.0608333' WHERE id_departamento = '15' AND id = '223'; -- CUBARÁ
UPDATE municipio SET latitud = '5.5333333', longitud = '-73.4666667' WHERE id_departamento = '15' AND id = '224'; -- CUCAITA
UPDATE municipio SET latitud = '5.25', longitud = '-73.75' WHERE id_departamento = '25' AND id = '224'; -- CUCUNUBÁ
UPDATE municipio SET latitud = '7.5', longitud = '-72.75' WHERE id_departamento = '54' AND id = '223'; -- CUCUTILLA
UPDATE municipio SET latitud = '4.25', longitud = '-73.3333333' WHERE id_departamento = '50' AND id = '226'; -- CUMARAL
UPDATE municipio SET latitud = '4.4444444', longitud = '-69.8002778' WHERE id_departamento = '99' AND id = '773'; -- CUMARIBO
UPDATE municipio SET latitud = '0.9166667', longitud = '-77.9166667' WHERE id_departamento = '52' AND id = '227'; -- CUMBAL
UPDATE municipio SET latitud = '1.6533333', longitud = '-77.5833333' WHERE id_departamento = '52' AND id = '233'; -- CUMBITARA
UPDATE municipio SET latitud = '4.0833333', longitud = '-74.6666667' WHERE id_departamento = '73' AND id = '226'; -- CUNDAY
UPDATE municipio SET latitud = '1.0352778', longitud = '-75.9247222' WHERE id_departamento = '18' AND id = '205'; -- CURILLO
UPDATE municipio SET latitud = '6.6666667', longitud = '-73.0' WHERE id_departamento = '68' AND id = '229'; -- CURITÍ
UPDATE municipio SET latitud = '9.2041667', longitud = '-73.5486111' WHERE id_departamento = '20' AND id = '228'; -- CURUMANI
UPDATE municipio SET latitud = '5.65', longitud = '-72.9166667' WHERE id_departamento = '15' AND id = '226'; -- CUÍTIVA
UPDATE municipio SET latitud = '7.6666667', longitud = '-75.3333333' WHERE id_departamento = '5' AND id = '120'; -- CÁCERES
UPDATE municipio SET latitud = '7.25', longitud = '-72.5833333' WHERE id_departamento = '54' AND id = '125'; -- CÁCOTA
UPDATE municipio SET latitud = '4.45', longitud = '-73.95' WHERE id_departamento = '25' AND id = '151'; -- CÁQUEZA
UPDATE municipio SET latitud = '5.4', longitud = '-76.6' WHERE id_departamento = '27' AND id = '160'; -- CÉRTEGUI
UPDATE municipio SET latitud = '5.75', longitud = '-73.25' WHERE id_departamento = '15' AND id = '204'; -- CÓMBITA
UPDATE municipio SET latitud = '0.7833333', longitud = '-77.3333333' WHERE id_departamento = '52' AND id = '215'; -- CÓRDOBA
UPDATE municipio SET latitud = '4.3905556', longitud = '-75.6888889' WHERE id_departamento = '63' AND id = '212'; -- CÓRDOBA
UPDATE municipio SET latitud = '9.5', longitud = '-74.9166667' WHERE id_departamento = '13' AND id = '212'; -- CÓRDOBA
UPDATE municipio SET latitud = '7.8833333', longitud = '-72.5052778' WHERE id_departamento = '54' AND id = '1'; -- CÚCUTA
UPDATE municipio SET latitud = '7.0', longitud = '-76.25' WHERE id_departamento = '5' AND id = '234'; -- DABEIBA
UPDATE municipio SET latitud = '3.6602778', longitud = '-76.6927778' WHERE id_departamento = '76' AND id = '233'; -- DAGUA
UPDATE municipio SET latitud = '11.2722222', longitud = '-73.3055556' WHERE id_departamento = '44' AND id = '90'; -- DIBULLA
UPDATE municipio SET latitud = '10.8977778', longitud = '-72.8869444' WHERE id_departamento = '44' AND id = '98'; -- DISTRACCIÓN
UPDATE municipio SET latitud = '3.6666667', longitud = '-74.75' WHERE id_departamento = '73' AND id = '236'; -- DOLORES
UPDATE municipio SET latitud='6.502', longitud='-75.3948' WHERE id='237' AND id_departamento='5'; --DON MATIAS, ANTIOQUIA
UPDATE municipio SET latitud = '4.8347222', longitud = '-75.6725' WHERE id_departamento = '66' AND id = '170'; -- DOSQUEBRADAS
UPDATE municipio SET latitud = '5.8269444', longitud = '-73.0202778' WHERE id_departamento = '15' AND id = '238'; -- DUITAMA
UPDATE municipio SET latitud = '7.75', longitud = '-72.6333333' WHERE id_departamento = '54' AND id = '239'; -- DURANIA
UPDATE municipio SET latitud = '6.3333333', longitud = '-75.75' WHERE id_departamento = '5' AND id = '240'; -- EBÉJICO
UPDATE municipio SET latitud='-1.3397668', longitud='-79.3666965' WHERE id='3' AND id_departamento='0'; --ECUADOR, EXTERIOR
UPDATE municipio SET latitud = '7.5941667', longitud = '-74.8119444' WHERE id_departamento = '5' AND id = '250'; -- EL BAGRE
UPDATE municipio SET latitud = '9.0455556', longitud = '-73.9747222' WHERE id_departamento = '47' AND id = '245'; -- EL BANCO
UPDATE municipio SET latitud = '4.75', longitud = '-76.25' WHERE id_departamento = '76' AND id = '246'; -- EL CAIRO
UPDATE municipio SET latitud = '4.4166667', longitud = '-73.6666667' WHERE id_departamento = '50' AND id = '245'; -- EL CALVARIO
UPDATE municipio SET latitud='6.398', longitud='-77.32538' WHERE id='245' AND id_departamento='27'; --EL CARMEN, CHOCÓ
UPDATE municipio SET latitud = '8.75', longitud = '-73.3333333' WHERE id_departamento = '54' AND id = '245'; -- EL CARMEN
UPDATE municipio SET latitud = '9.75', longitud = '-75.1666667' WHERE id_departamento = '13' AND id = '244'; -- EL CARMEN DE BOLÍVAR
UPDATE municipio SET latitud = '6.0', longitud = '-73.511' WHERE id_departamento = '68' AND id = '235'; -- EL CARMEN DE CHUCURÍ
UPDATE municipio SET latitud = '3.5680556', longitud = '-73.7838889' WHERE id_departamento = '50' AND id = '251'; -- EL CASTILLO
UPDATE municipio SET latitud = '3.6666667', longitud = '-76.1666667' WHERE id_departamento = '76' AND id = '248'; -- EL CERRITO
UPDATE municipio SET latitud = '2.3333333', longitud = '-78.35' WHERE id_departamento = '52' AND id = '250'; -- EL CHARCO
UPDATE municipio SET latitud = '6.4166667', longitud = '-72.4166667' WHERE id_departamento = '15' AND id = '244'; -- EL COCUY
UPDATE municipio SET latitud = '4.5833333', longitud = '-74.45' WHERE id_departamento = '25' AND id = '245'; -- EL COLEGIO
UPDATE municipio SET latitud = '10.1505556', longitud = '-73.965' WHERE id_departamento = '20' AND id = '238'; -- EL COPEY
UPDATE municipio SET latitud = '1.6794444', longitud = '-75.2791667' WHERE id_departamento = '18' AND id = '247'; -- EL DONCELLO
UPDATE municipio SET latitud = '2.7791667', longitud = '-72.8719444' WHERE id_departamento = '50' AND id = '270'; -- EL DORADO
UPDATE municipio SET latitud = '4.5', longitud = '-76.25' WHERE id_departamento = '76' AND id = '250'; -- EL DOVIO
UPDATE municipio SET latitud='-1.9963', longitud='-72.68959' WHERE id='263' AND id_departamento='91'; --EL ENCANTO, AMAZONAS
UPDATE municipio SET latitud = '6.5833333', longitud = '-72.4166667' WHERE id_departamento = '15' AND id = '248'; -- EL ESPINO
UPDATE municipio SET latitud = '6.3333333', longitud = '-73.4666667' WHERE id_departamento = '68' AND id = '245'; -- EL GUACAMAYO
UPDATE municipio SET latitud = '10.0833333', longitud = '-74.9166667' WHERE id_departamento = '13' AND id = '248'; -- EL GUAMO
UPDATE municipio SET latitud = '4.2586111', longitud = '-77.3675' WHERE id_departamento = '27' AND id = '250'; -- EL LITORAL DEL SAN JUAN
UPDATE municipio SET latitud = '10.6533333', longitud = '-72.9241667' WHERE id_departamento = '44' AND id = '110'; -- EL MOLINO
UPDATE municipio SET latitud = '9.6622222', longitud = '-73.7519444' WHERE id_departamento = '20' AND id = '250'; -- EL PASO
UPDATE municipio SET latitud = '1.564', longitud = '-75.332' WHERE id_departamento = '18' AND id = '256'; -- EL PAUJÍL
UPDATE municipio SET latitud = '1.4333333', longitud = '-77.4333333' WHERE id_departamento = '52' AND id = '254'; -- EL PEÑOL
UPDATE municipio SET latitud = '8.9833333', longitud = '-73.95' WHERE id_departamento = '13' AND id = '268'; -- EL PEÑÓN
UPDATE municipio SET latitud = '6.55', longitud = '-72.8333333' WHERE id_departamento = '68' AND id = '250'; -- EL PEÑÓN
UPDATE municipio SET latitud = '5.3333333', longitud = '-74.3333333' WHERE id_departamento = '25' AND id = '258'; -- EL PEÑÓN
UPDATE municipio SET latitud = '10.3333333', longitud = '-74.6666667' WHERE id_departamento = '47' AND id = '258'; -- EL PIÑÓN
UPDATE municipio SET latitud = '7.4766667', longitud = '-73.2080556' WHERE id_departamento = '68' AND id = '255'; -- EL PLAYÓN
UPDATE municipio SET latitud = '2.3305556', longitud = '-72.6277778' WHERE id_departamento = '95' AND id = '25'; -- EL RETORNO
UPDATE municipio SET latitud = '10.6166667', longitud = '-74.2666667' WHERE id_departamento = '47' AND id = '268'; -- EL RETÉN
UPDATE municipio SET latitud = '9.1', longitud = '-75.2' WHERE id_departamento = '70' AND id = '233'; -- EL ROBLE
UPDATE municipio SET latitud = '4.85', longitud = '-74.2666667' WHERE id_departamento = '25' AND id = '260'; -- EL ROSAL
UPDATE municipio SET latitud = '1.8333333', longitud = '-77.3333333' WHERE id_departamento = '52' AND id = '256'; -- EL ROSARIO
UPDATE municipio SET latitud='1.2991', longitud='-77.47585' WHERE id='258' AND id_departamento='52'; --EL TABLON, NARIÑO
UPDATE municipio SET latitud = '2.5', longitud = '-77.0' WHERE id_departamento = '19' AND id = '256'; -- EL TAMBO
UPDATE municipio SET latitud = '1.4', longitud = '-77.3333333' WHERE id_departamento = '52' AND id = '260'; -- EL TAMBO
UPDATE municipio SET latitud = '8.5847222', longitud = '-73.0883333' WHERE id_departamento = '54' AND id = '250'; -- EL TARRA
UPDATE municipio SET latitud = '7.9355556', longitud = '-72.605' WHERE id_departamento = '54' AND id = '261'; -- EL ZULIA
UPDATE municipio SET latitud = '4.9166667', longitud = '-76.0833333' WHERE id_departamento = '76' AND id = '243'; -- EL ÁGUILA
UPDATE municipio SET latitud = '2.0833333', longitud = '-76.0' WHERE id_departamento = '41' AND id = '244'; -- ELÍAS
UPDATE municipio SET latitud = '6.1666667', longitud = '-73.0' WHERE id_departamento = '68' AND id = '264'; -- ENCINO
UPDATE municipio SET latitud = '6.75', longitud = '-72.6333333' WHERE id_departamento = '68' AND id = '266'; -- ENCISO
UPDATE municipio SET latitud = '6.5833333', longitud = '-75.5833333' WHERE id_departamento = '5' AND id = '264'; -- ENTRERRÍOS
UPDATE municipio SET latitud = '6.1730556', longitud = '-75.5638889' WHERE id_departamento = '5' AND id = '266'; -- ENVIGADO
UPDATE municipio SET latitud = '4.2', longitud = '-74.8333333' WHERE id_departamento = '73' AND id = '268'; -- ESPINAL
UPDATE municipio SET latitud = '4.8166667', longitud = '-74.3666667' WHERE id_departamento = '25' AND id = '269'; -- FACATATIVÁ
UPDATE municipio SET latitud = '5.1333333', longitud = '-75.0' WHERE id_departamento = '73' AND id = '270'; -- FALAN
UPDATE municipio SET latitud = '5.3', longitud = '-75.6' WHERE id_departamento = '17' AND id = '272'; -- FILADELFIA
UPDATE municipio SET latitud = '4.6666667', longitud = '-75.6333333' WHERE id_departamento = '63' AND id = '272'; -- FILANDIA
UPDATE municipio SET latitud = '5.75', longitud = '-72.9666667' WHERE id_departamento = '15' AND id = '272'; -- FIRAVITOBA
UPDATE municipio SET latitud = '4.25', longitud = '-74.8333333' WHERE id_departamento = '73' AND id = '275'; -- FLANDES
UPDATE municipio SET latitud = '1.6175', longitud = '-75.6175' WHERE id_departamento = '18' AND id = '1'; -- FLORENCIA
UPDATE municipio SET latitud = '1.6944444', longitud = '-77.0758333' WHERE id_departamento = '19' AND id = '290'; -- FLORENCIA
UPDATE municipio SET latitud = '5.9166667', longitud = '-72.9166667' WHERE id_departamento = '15' AND id = '276'; -- FLORESTA
UPDATE municipio SET latitud = '3.3275', longitud = '-76.2386111' WHERE id_departamento = '76' AND id = '275'; -- FLORIDA
UPDATE municipio SET latitud = '7.0647222', longitud = '-73.0897222' WHERE id_departamento = '68' AND id = '276'; -- FLORIDABLANCA
UPDATE municipio SET latitud = '5.8047222', longitud = '-73.9741667' WHERE id_departamento = '68' AND id = '271'; -- FLORIÁN
UPDATE municipio SET latitud = '10.8333333', longitud = '-72.8333333' WHERE id_departamento = '44' AND id = '279'; -- FONSECA
UPDATE municipio SET latitud = '6.7461111', longitud = '-71.8566667' WHERE id_departamento = '81' AND id = '300'; -- FORTUL
UPDATE municipio SET latitud = '4.3333333', longitud = '-73.9166667' WHERE id_departamento = '25' AND id = '281'; -- FOSCA
UPDATE municipio SET latitud = '2.1019444', longitud = '-78.7216667' WHERE id_departamento = '52' AND id = '520'; -- FRANCISCO PIZARRO
UPDATE municipio SET latitud = '5.9166667', longitud = '-75.6666667' WHERE id_departamento = '5' AND id = '282'; -- FREDONIA
UPDATE municipio SET latitud = '5.1666667', longitud = '-75.0833333' WHERE id_departamento = '73' AND id = '283'; -- FRESNO
UPDATE municipio SET latitud = '6.6666667', longitud = '-76.3333333' WHERE id_departamento = '5' AND id = '284'; -- FRONTINO
UPDATE municipio SET latitud = '3.4594444', longitud = '-73.6127778' WHERE id_departamento = '50' AND id = '287'; -- FUENTE DE ORO
UPDATE municipio SET latitud = '10.4166667', longitud = '-73.9166667' WHERE id_departamento = '47' AND id = '288'; -- FUNDACIÓN
UPDATE municipio SET latitud = '0.95', longitud = '-77.3333333' WHERE id_departamento = '52' AND id = '287'; -- FUNES
UPDATE municipio SET latitud = '4.7833333', longitud = '-74.2' WHERE id_departamento = '25' AND id = '286'; -- FUNZA
UPDATE municipio SET latitud = '4.4166667', longitud = '-74.4' WHERE id_departamento = '25' AND id = '290'; -- FUSAGASUGÁ
UPDATE municipio SET latitud = '4.5833333', longitud = '-73.8333333' WHERE id_departamento = '25' AND id = '279'; -- FÓMEQUE
UPDATE municipio SET latitud = '5.4166667', longitud = '-73.8' WHERE id_departamento = '25' AND id = '288'; -- FÚQUENE
UPDATE municipio SET latitud = '4.6666667', longitud = '-73.5' WHERE id_departamento = '25' AND id = '293'; -- GACHALÁ
UPDATE municipio SET latitud = '5.0833333', longitud = '-73.8833333' WHERE id_departamento = '25' AND id = '295'; -- GACHANCIPÁ
UPDATE municipio SET latitud = '5.75', longitud = '-73.5' WHERE id_departamento = '15' AND id = '293'; -- GACHANTIVÁ
UPDATE municipio SET latitud = '4.9166667', longitud = '-73.6666667' WHERE id_departamento = '25' AND id = '297'; -- GACHETÁ
UPDATE municipio SET latitud = '10.9166667', longitud = '-74.8333333' WHERE id_departamento = '8' AND id = '296'; -- GALAPA
UPDATE municipio SET latitud = '9.1630556', longitud = '-75.0525' WHERE id_departamento = '70' AND id = '235'; -- GALERAS
UPDATE municipio SET latitud = '6.7', longitud = '-73.3' WHERE id_departamento = '68' AND id = '296'; -- GALÁN
UPDATE municipio SET latitud = '4.75', longitud = '-73.6' WHERE id_departamento = '25' AND id = '299'; -- GAMA
UPDATE municipio SET latitud = '8.3333333', longitud = '-73.6666667' WHERE id_departamento = '20' AND id = '295'; -- GAMARRA
UPDATE municipio SET latitud = '5.1333333', longitud = '-73.3' WHERE id_departamento = '15' AND id = '299'; -- GARAGOA
UPDATE municipio SET latitud = '2.25', longitud = '-75.5833333' WHERE id_departamento = '41' AND id = '298'; -- GARZÓN
UPDATE municipio SET latitud = '2.5', longitud = '-75.5' WHERE id_departamento = '41' AND id = '306'; -- GIGANTE
UPDATE municipio SET latitud = '3.75', longitud = '-76.1666667' WHERE id_departamento = '76' AND id = '306'; -- GINEBRA
UPDATE municipio SET latitud = '6.6666667', longitud = '-75.9166667' WHERE id_departamento = '5' AND id = '306'; -- GIRALDO
UPDATE municipio SET latitud = '4.3030556', longitud = '-74.8008333' WHERE id_departamento = '25' AND id = '307'; -- GIRARDOT
UPDATE municipio SET latitud = '6.4166667', longitud = '-75.45' WHERE id_departamento = '5' AND id = '308'; -- GIRARDOTA
UPDATE municipio SET latitud='7.049716', longitud='-73.29060' WHERE id='307' AND id_departamento='68'; --GIRÓN, SANTANDER
UPDATE municipio SET latitud = '8.4', longitud = '-73.3333333' WHERE id_departamento = '20' AND id = '310'; -- GONZÁLEZ
UPDATE municipio SET latitud = '7.9166667', longitud = '-72.75' WHERE id_departamento = '54' AND id = '313'; -- GRAMALOTE
UPDATE municipio SET latitud = '6.1666667', longitud = '-75.1333333' WHERE id_departamento = '5' AND id = '313'; -- GRANADA
UPDATE municipio SET latitud = '3.5386111', longitud = '-73.7005556' WHERE id_departamento = '50' AND id = '313'; -- GRANADA
UPDATE municipio SET latitud = '5.0666667', longitud = '-74.5666667' WHERE id_departamento = '25' AND id = '312'; -- GRANADA
UPDATE municipio SET latitud = '7.0', longitud = '-72.7666667' WHERE id_departamento = '68' AND id = '318'; -- GUACA
UPDATE municipio SET latitud = '6.5', longitud = '-72.5' WHERE id_departamento = '15' AND id = '317'; -- GUACAMAYAS
UPDATE municipio SET latitud = '3.7694444', longitud = '-76.3380556' WHERE id_departamento = '76' AND id = '318'; -- GUACARÍ
UPDATE municipio SET latitud = '3.1333333', longitud = '-76.3833333' WHERE id_departamento = '19' AND id = '300'; -- GUACHENÉ
UPDATE municipio SET latitud = '5.4166667', longitud = '-73.7' WHERE id_departamento = '25' AND id = '317'; -- GUACHETÁ
UPDATE municipio SET latitud = '1.0', longitud = '-77.75' WHERE id_departamento = '52' AND id = '317'; -- GUACHUCAL
UPDATE municipio SET latitud = '3.9022222', longitud = '-76.3027778' WHERE id_departamento = '76' AND id = '111'; -- GUADALAJARA DE BUGA
UPDATE municipio SET latitud = '6.3', longitud = '-73.3333333' WHERE id_departamento = '68' AND id = '320'; -- GUADALUPE
UPDATE municipio SET latitud = '6.8175', longitud = '-75.2441667' WHERE id_departamento = '5' AND id = '315'; -- GUADALUPE
UPDATE municipio SET latitud = '2.0833333', longitud = '-75.6666667' WHERE id_departamento = '41' AND id = '319'; -- GUADALUPE
UPDATE municipio SET latitud = '5.25', longitud = '-74.6666667' WHERE id_departamento = '25' AND id = '320'; -- GUADUAS
UPDATE municipio SET latitud = '1.15', longitud = '-77.5' WHERE id_departamento = '52' AND id = '320'; -- GUAITARILLA
UPDATE municipio SET latitud = '0.8333333', longitud = '-77.5833333' WHERE id_departamento = '52' AND id = '323'; -- GUALMATÁN
UPDATE municipio SET latitud = '3.9166667', longitud = '-74.0' WHERE id_departamento = '50' AND id = '318'; -- GUAMAL
UPDATE municipio SET latitud = '9.25', longitud = '-74.1666667' WHERE id_departamento = '47' AND id = '318'; -- GUAMAL
UPDATE municipio SET latitud = '4.0833333', longitud = '-74.9166667' WHERE id_departamento = '73' AND id = '319'; -- GUAMO
UPDATE municipio SET latitud = '6.3833333', longitud = '-73.25' WHERE id_departamento = '68' AND id = '322'; -- GUAPOTÁ
UPDATE municipio SET latitud = '2.4166667', longitud = '-77.6' WHERE id_departamento = '19' AND id = '318'; -- GUAPÍ
UPDATE municipio SET latitud = '8.4697222', longitud = '-75.5383333' WHERE id_departamento = '70' AND id = '265'; -- GUARANDA
UPDATE municipio SET latitud = '6.25', longitud = '-75.4166667' WHERE id_departamento = '5' AND id = '318'; -- GUARNE
UPDATE municipio SET latitud = '4.8333333', longitud = '-73.8333333' WHERE id_departamento = '25' AND id = '322'; -- GUASCA
UPDATE municipio SET latitud = '6.25', longitud = '-75.1666667' WHERE id_departamento = '5' AND id = '321'; -- GUATAPÉ
UPDATE municipio SET latitud = '4.5833333', longitud = '-74.8' WHERE id_departamento = '25' AND id = '324'; -- GUATAQUÍ
UPDATE municipio SET latitud = '4.9666667', longitud = '-73.75' WHERE id_departamento = '25' AND id = '326'; -- GUATAVITA
UPDATE municipio SET latitud = '5.0833333', longitud = '-73.5' WHERE id_departamento = '15' AND id = '322'; -- GUATEQUE
UPDATE municipio SET latitud = '6.0', longitud = '-73.65' WHERE id_departamento = '68' AND id = '324'; -- GUAVATÁ
UPDATE municipio SET latitud='3.90399', longitud='-67.9026' WHERE id='343' AND id_departamento='94'; --GUAVIARE, GUAINÍA
UPDATE municipio SET latitud = '4.8813889', longitud = '-74.4702778' WHERE id_departamento = '25' AND id = '328'; -- GUAYABAL DE SIQUIMA
UPDATE municipio SET latitud = '4.2163889', longitud = '-73.8133333' WHERE id_departamento = '25' AND id = '335'; -- GUAYABETAL
UPDATE municipio SET latitud = '5.0', longitud = '-73.5' WHERE id_departamento = '15' AND id = '325'; -- GUAYATÁ
UPDATE municipio SET latitud = '4.25', longitud = '-74.0' WHERE id_departamento = '25' AND id = '339'; -- GUTIÉRREZ
UPDATE municipio SET latitud = '5.3333333', longitud = '-75.8333333' WHERE id_departamento = '66' AND id = '318'; -- GUÁTICA
UPDATE municipio SET latitud = '6.0', longitud = '-73.25' WHERE id_departamento = '68' AND id = '298'; -- GÁMBITA
UPDATE municipio SET latitud = '5.85', longitud = '-72.75' WHERE id_departamento = '15' AND id = '296'; -- GÁMEZA
UPDATE municipio SET latitud = '4.25', longitud = '-75.6666667' WHERE id_departamento = '63' AND id = '302'; -- GÉNOVA
UPDATE municipio SET latitud = '6.75', longitud = '-75.8333333' WHERE id_departamento = '5' AND id = '310'; -- GÓMEZ PLATA
UPDATE municipio SET latitud = '6.0833333', longitud = '-73.5' WHERE id_departamento = '68' AND id = '327'; -- GÜEPSA
UPDATE municipio SET latitud = '6.75', longitud = '-72.1666667' WHERE id_departamento = '15' AND id = '332'; -- GÜICÁN
UPDATE municipio SET latitud = '8.5', longitud = '-73.0833333' WHERE id_departamento = '54' AND id = '344'; -- HACARÍ
UPDATE municipio SET latitud = '8.9586111', longitud = '-74.0808333' WHERE id_departamento = '13' AND id = '300'; -- HATILLO DE LOBA
UPDATE municipio SET latitud = '6.5833333', longitud = '-73.3333333' WHERE id_departamento = '68' AND id = '344'; -- HATO
UPDATE municipio SET latitud = '6.1555556', longitud = '-71.7655556' WHERE id_departamento = '85' AND id = '125'; -- HATO COROZAL
UPDATE municipio SET latitud = '11.0694444', longitud = '-72.7669444' WHERE id_departamento = '44' AND id = '378'; -- HATONUEVO
UPDATE municipio SET latitud = '6.2333333', longitud = '-75.75' WHERE id_departamento = '5' AND id = '347'; -- HELICONIA
UPDATE municipio SET latitud = '7.5', longitud = '-72.4666667' WHERE id_departamento = '54' AND id = '347'; -- HERRÁN
UPDATE municipio SET latitud = '5.0833333', longitud = '-75.1666667' WHERE id_departamento = '73' AND id = '347'; -- HERVEO
UPDATE municipio SET latitud = '5.8025', longitud = '-75.9116667' WHERE id_departamento = '5' AND id = '353'; -- HISPANIA
UPDATE municipio SET latitud = '2.5833333', longitud = '-75.5' WHERE id_departamento = '41' AND id = '349'; -- HOBO
UPDATE municipio SET latitud = '5.25', longitud = '-74.8333333' WHERE id_departamento = '73' AND id = '349'; -- HONDA
UPDATE municipio SET latitud = '4.4388889', longitud = '-75.2322222' WHERE id_departamento = '73' AND id = '1'; -- IBAGUÉ
UPDATE municipio SET latitud = '4.1666667', longitud = '-74.55' WHERE id_departamento = '73' AND id = '352'; -- ICONONZO
UPDATE municipio SET latitud = '0.95', longitud = '-77.5333333' WHERE id_departamento = '52' AND id = '352'; -- ILES
UPDATE municipio SET latitud = '1.05', longitud = '-77.5' WHERE id_departamento = '52' AND id = '354'; -- IMUÉS
UPDATE municipio SET latitud = '2.6666667', longitud = '-76.0833333' WHERE id_departamento = '19' AND id = '355'; -- INZÁ
UPDATE municipio SET latitud = '3.8652778', longitud = '-67.9238889' WHERE id_departamento = '94' AND id = '1'; -- INÍRIDA
UPDATE municipio SET latitud = '0.8302778', longitud = '-77.6444444' WHERE id_departamento = '52' AND id = '356'; -- IPIALES
UPDATE municipio SET latitud = '2.0833333', longitud = '-76.25' WHERE id_departamento = '41' AND id = '359'; -- ISNOS
UPDATE municipio SET latitud = '5.1588889', longitud = '-76.6916667' WHERE id_departamento = '27' AND id = '361'; -- ISTMINA
UPDATE municipio SET latitud='5.98212', longitud='-75.26084' WHERE id='360' AND id_departamento='5'; --ITAGUI, ANTIOQUIA
UPDATE municipio SET latitud = '7.25', longitud = '-76.0' WHERE id_departamento = '5' AND id = '361'; -- ITUANGO
UPDATE municipio SET latitud = '5.6666667', longitud = '-72.9166667' WHERE id_departamento = '15' AND id = '362'; -- IZA
UPDATE municipio SET latitud = '2.85', longitud = '-76.3166667' WHERE id_departamento = '19' AND id = '364'; -- JAMBALÓ
UPDATE municipio SET latitud = '3.1666667', longitud = '-76.5833333' WHERE id_departamento = '76' AND id = '364'; -- JAMUNDI
UPDATE municipio SET latitud = '5.5833333', longitud = '-75.8333333' WHERE id_departamento = '5' AND id = '364'; -- JARDÍN
UPDATE municipio SET latitud = '5.3888889', longitud = '-73.3680556' WHERE id_departamento = '15' AND id = '367'; -- JENESANO
UPDATE municipio SET latitud = '6.2', longitud = '-72.5833333' WHERE id_departamento = '15' AND id = '368'; -- JERICÓ
UPDATE municipio SET latitud = '5.75', longitud = '-75.75' WHERE id_departamento = '5' AND id = '368'; -- JERICÓ
UPDATE municipio SET latitud = '4.6666667', longitud = '-74.6666667' WHERE id_departamento = '25' AND id = '368'; -- JERUSALÉN
UPDATE municipio SET latitud = '5.9166667', longitud = '-73.8333333' WHERE id_departamento = '68' AND id = '368'; -- JESÚS MARÍA
UPDATE municipio SET latitud = '6.75', longitud = '-73.0666667' WHERE id_departamento = '68' AND id = '370'; -- JORDAN
UPDATE municipio SET latitud='10.82649', longitud='-75.08635' WHERE id='372' AND id_departamento='8'; --JUAN DE ACOSTA, ATLÁNTICO
UPDATE municipio SET latitud = '4.75', longitud = '-73.6666667' WHERE id_departamento = '25' AND id = '372'; -- JUNÍN
UPDATE municipio SET latitud = '7.0', longitud = '-77.6666667' WHERE id_departamento = '27' AND id = '372'; -- JURADÓ
UPDATE municipio SET latitud='8.04076', longitud='-75.31354' WHERE id='350' AND id_departamento='23'; --LA APARTADA, CÓRDOBA
UPDATE municipio SET latitud = '2.25', longitud = '-76.1666667' WHERE id_departamento = '41' AND id = '378'; -- LA ARGENTINA
UPDATE municipio SET latitud = '5.8613889', longitud = '-73.9683333' WHERE id_departamento = '68' AND id = '377'; -- LA BELLEZA
UPDATE municipio SET latitud = '4.75', longitud = '-73.9166667' WHERE id_departamento = '25' AND id = '377'; -- LA CALERA
UPDATE municipio SET latitud = '5.15', longitud = '-73.45' WHERE id_departamento = '15' AND id = '380'; -- LA CAPILLA
UPDATE municipio SET latitud = '6.0', longitud = '-75.4166667' WHERE id_departamento = '5' AND id = '376'; -- LA CEJA
UPDATE municipio SET latitud = '4.9666667', longitud = '-76.0' WHERE id_departamento = '66' AND id = '383'; -- LA CELIA
UPDATE municipio SET latitud='-1.28237', longitud='-72.6390548' WHERE id='405' AND id_departamento='91'; --LA CHORRERA, AMAZONAS
UPDATE municipio SET latitud = '1.55', longitud = '-76.8833333' WHERE id_departamento = '52' AND id = '378'; -- LA CRUZ
UPDATE municipio SET latitud='3.64952', longitud='-76.56845' WHERE id='377' AND id_departamento='76'; --LA CUMBRE, VALLE DEL CAUCA
UPDATE municipio SET latitud = '5.5333333', longitud = '-74.7' WHERE id_departamento = '17' AND id = '380'; -- LA DORADA
UPDATE municipio SET latitud = '8.1666667', longitud = '-72.4666667' WHERE id_departamento = '54' AND id = '385'; -- LA ESPERANZA
UPDATE municipio SET latitud = '6.1666667', longitud = '-75.6666667' WHERE id_departamento = '5' AND id = '380'; -- LA ESTRELLA
UPDATE municipio SET latitud = '1.25', longitud = '-77.4166667' WHERE id_departamento = '52' AND id = '381'; -- LA FLORIDA
UPDATE municipio SET latitud = '8.5833333', longitud = '-73.5833333' WHERE id_departamento = '20' AND id = '383'; -- LA GLORIA
UPDATE municipio SET latitud='1.24308', longitud='-66.8675' WHERE id='885' AND id_departamento='94'; --LA GUADALUPE, GUAINÍA
UPDATE municipio SET latitud = '9.5641667', longitud = '-73.3375' WHERE id_departamento = '20' AND id = '400'; -- LA JAGUA DE IBIRICO
UPDATE municipio SET latitud = '10.5166667', longitud = '-73.0833333' WHERE id_departamento = '44' AND id = '420'; -- LA JAGUA DEL PILAR
UPDATE municipio SET latitud = '1.4777778', longitud = '-77.5838889' WHERE id_departamento = '52' AND id = '385'; -- LA LLANADA
UPDATE municipio SET latitud = '2.8536111', longitud = '-73.9436111' WHERE id_departamento = '50' AND id = '350'; -- LA MACARENA
UPDATE municipio SET latitud = '5.4019444', longitud = '-75.8847222' WHERE id_departamento = '17' AND id = '388'; -- LA MERCED
UPDATE municipio SET latitud = '4.6666667', longitud = '-74.5' WHERE id_departamento = '25' AND id = '386'; -- LA MESA
UPDATE municipio SET latitud = '1.5833333', longitud = '-75.25' WHERE id_departamento = '18' AND id = '410'; -- LA MONTAÑITA
UPDATE municipio SET latitud = '5.4166667', longitud = '-74.4166667' WHERE id_departamento = '25' AND id = '394'; -- LA PALMA
UPDATE municipio SET latitud = '6.25', longitud = '-73.4666667' WHERE id_departamento = '68' AND id = '397'; -- LA PAZ
UPDATE municipio SET latitud = '10.1666667', longitud = '-73.25' WHERE id_departamento = '20' AND id = '621'; -- LA PAZ
UPDATE municipio SET latitud='-1.32238', longitud='-69.57839' WHERE id='407' AND id_departamento='91'; --LA PEDRERA, AMAZONAS
UPDATE municipio SET latitud = '5.25', longitud = '-74.4166667' WHERE id_departamento = '25' AND id = '398'; -- LA PEÑA
UPDATE municipio SET latitud = '5.75', longitud = '-75.6' WHERE id_departamento = '5' AND id = '390'; -- LA PINTADA
UPDATE municipio SET latitud = '2.4166667', longitud = '-76.1666667' WHERE id_departamento = '41' AND id = '396'; -- LA PLATA
UPDATE municipio SET latitud = '8.25', longitud = '-73.1666667' WHERE id_departamento = '54' AND id = '398'; -- LA PLAYA
UPDATE municipio SET latitud = '5.4905556', longitud = '-70.4091667' WHERE id_departamento = '99' AND id = '524'; -- LA PRIMAVERA
UPDATE municipio SET latitud = '6.1852778', longitud = '-72.3444444' WHERE id_departamento = '85' AND id = '136'; -- LA SALINA
UPDATE municipio SET latitud = '2.25', longitud = '-76.8333333' WHERE id_departamento = '19' AND id = '392'; -- LA SIERRA
UPDATE municipio SET latitud = '4.45', longitud = '-75.8' WHERE id_departamento = '63' AND id = '401'; -- LA TEBAIDA
UPDATE municipio SET latitud = '2.4108333', longitud = '-78.2427778' WHERE id_departamento = '52' AND id = '390'; -- LA TOLA
UPDATE municipio SET latitud = '5.9166667', longitud = '-75.3333333' WHERE id_departamento = '5' AND id = '400'; -- LA UNIÓN
UPDATE municipio SET latitud = '8.8605556', longitud = '-75.2805556' WHERE id_departamento = '70' AND id = '400'; -- LA UNIÓN
UPDATE municipio SET latitud = '1.5833333', longitud = '-77.0833333' WHERE id_departamento = '52' AND id = '399'; -- LA UNIÓN
UPDATE municipio SET latitud = '4.5358333', longitud = '-76.1066667' WHERE id_departamento = '76' AND id = '400'; -- LA UNIÓN
UPDATE municipio SET latitud='3.2012021', longitud='-74.3836594' WHERE id='370' AND id_departamento='50'; --LA URIBE, META
UPDATE municipio SET latitud = '6.3333333', longitud = '-72.5' WHERE id_departamento = '15' AND id = '403'; -- LA UVITA
UPDATE municipio SET latitud = '2.0833333', longitud = '-76.8333333' WHERE id_departamento = '19' AND id = '397'; -- LA VEGA
UPDATE municipio SET latitud = '5.0', longitud = '-74.35' WHERE id_departamento = '25' AND id = '402'; -- LA VEGA
UPDATE municipio SET latitud = '5.5258333', longitud = '-74.2361111' WHERE id_departamento = '15' AND id = '401'; -- LA VICTORIA
UPDATE municipio SET latitud = '4.5238889', longitud = '-76.0411111' WHERE id_departamento = '76' AND id = '403'; -- LA VICTORIA
UPDATE municipio SET latitud='-0.12072', longitud='-71.082951' WHERE id='430' AND id_departamento='91'; --LA VICTORIA, AMAZONAS
UPDATE municipio SET latitud = '4.9166667', longitud = '-75.8333333' WHERE id_departamento = '66' AND id = '400'; -- LA VIRGINIA
UPDATE municipio SET latitud = '7.3333333', longitud = '-72.5' WHERE id_departamento = '54' AND id = '377'; -- LABATECA
UPDATE municipio SET latitud = '5.5833333', longitud = '-72.5833333' WHERE id_departamento = '15' AND id = '377'; -- LABRANZAGRANDE
UPDATE municipio SET latitud = '6.2238889', longitud = '-73.8127778' WHERE id_departamento = '68' AND id = '385'; -- LANDÁZURI
UPDATE municipio SET latitud = '7.4166667', longitud = '-73.4166667' WHERE id_departamento = '68' AND id = '406'; -- LEBRÍJA
UPDATE municipio SET latitud = '1.9375', longitud = '-77.3080556' WHERE id_departamento = '52' AND id = '405'; -- LEIVA
UPDATE municipio SET latitud = '3.5136111', longitud = '-74.0516667' WHERE id_departamento = '50' AND id = '400'; -- LEJANÍAS
UPDATE municipio SET latitud = '5.3333333', longitud = '-73.6666667' WHERE id_departamento = '25' AND id = '407'; -- LENGUAZAQUE
UPDATE municipio SET latitud = '-4.2152778', longitud = '-69.9405556' WHERE id_departamento = '91' AND id = '1'; -- LETICIA
UPDATE municipio SET latitud = '6.75', longitud = '-75.75' WHERE id_departamento = '5' AND id = '411'; -- LIBORINA
UPDATE municipio SET latitud = '1.3833333', longitud = '-77.45' WHERE id_departamento = '52' AND id = '411'; -- LINARES
UPDATE municipio SET latitud = '5.5833333', longitud = '-76.4166667' WHERE id_departamento = '27' AND id = '413'; -- LLORÓ
UPDATE municipio SET latitud = '9.1666667', longitud = '-75.8333333' WHERE id_departamento = '23' AND id = '417'; -- LORICA
UPDATE municipio SET latitud = '1.5155556', longitud = '-77.495' WHERE id_departamento = '52' AND id = '418'; -- LOS ANDES
UPDATE municipio SET latitud = '8.9', longitud = '-76.3597222' WHERE id_departamento = '23' AND id = '419'; -- LOS CÓRDOBAS
UPDATE municipio SET latitud = '9.3811111', longitud = '-75.2713889' WHERE id_departamento = '70' AND id = '418'; -- LOS PALMITOS
UPDATE municipio SET latitud = '7.8383333', longitud = '-72.5133333' WHERE id_departamento = '54' AND id = '405'; -- LOS PATIOS
UPDATE municipio SET latitud = '6.9166667', longitud = '-73.0833333' WHERE id_departamento = '68' AND id = '418'; -- LOS SANTOS
UPDATE municipio SET latitud = '7.9666667', longitud = '-72.8333333' WHERE id_departamento = '54' AND id = '418'; -- LOURDES
UPDATE municipio SET latitud = '10.6141667', longitud = '-75.1461111' WHERE id_departamento = '8' AND id = '421'; -- LURUACO
UPDATE municipio SET latitud = '4.9166667', longitud = '-74.9166667' WHERE id_departamento = '73' AND id = '408'; -- LÉRIDA
UPDATE municipio SET latitud = '4.9166667', longitud = '-75.1666667' WHERE id_departamento = '73' AND id = '411'; -- LÍBANO
UPDATE municipio SET latitud = '2.4333333', longitud = '-76.8' WHERE id_departamento = '19' AND id = '418'; -- LÓPEZ
UPDATE municipio SET latitud = '4.9166667', longitud = '-73.25' WHERE id_departamento = '15' AND id = '425'; -- MACANAL
UPDATE municipio SET latitud = '6.5833333', longitud = '-72.5333333' WHERE id_departamento = '68' AND id = '425'; -- MACARAVITA
UPDATE municipio SET latitud = '6.5', longitud = '-74.75' WHERE id_departamento = '5' AND id = '425'; -- MACEO
UPDATE municipio SET latitud = '5.0833333', longitud = '-73.6166667' WHERE id_departamento = '25' AND id = '426'; -- MACHETÁ
UPDATE municipio SET latitud = '4.8333333', longitud = '-74.3' WHERE id_departamento = '25' AND id = '430'; -- MADRID
UPDATE municipio SET latitud = '9.2413889', longitud = '-74.7533333' WHERE id_departamento = '13' AND id = '430'; -- MAGANGUÉ
UPDATE municipio SET latitud = '1.9166667', longitud = '-77.8333333' WHERE id_departamento = '52' AND id = '427'; -- MAGüI
UPDATE municipio SET latitud = '10.1666667', longitud = '-75.1666667' WHERE id_departamento = '13' AND id = '433'; -- MAHATES
UPDATE municipio SET latitud = '11.3841667', longitud = '-72.2441667' WHERE id_departamento = '44' AND id = '430'; -- MAICAO
UPDATE municipio SET latitud = '8.5', longitud = '-74.6666667' WHERE id_departamento = '70' AND id = '429'; -- MAJAGUAL
UPDATE municipio SET latitud = '10.8588889', longitud = '-74.7730556' WHERE id_departamento = '8' AND id = '433'; -- MALAMBO
UPDATE municipio SET latitud = '1.0833333', longitud = '-77.8166667' WHERE id_departamento = '52' AND id = '435'; -- MALLAMA
UPDATE municipio SET latitud = '10.5', longitud = '-75.0' WHERE id_departamento = '8' AND id = '436'; -- MANATÍ
UPDATE municipio SET latitud = '11.7791667', longitud = '-72.4494444' WHERE id_departamento = '44' AND id = '560'; -- MANAURE
UPDATE municipio SET latitud='10.385', longitud='-73.0287' WHERE id='443' AND id_departamento='20'; --MANAURE BALCON DEL CESAR, CESAR
UPDATE municipio SET latitud = '5.07', longitud = '-75.5205556' WHERE id_departamento = '17' AND id = '1'; -- MANIZALES
UPDATE municipio SET latitud = '5.0833333', longitud = '-73.5833333' WHERE id_departamento = '25' AND id = '436'; -- MANTA
UPDATE municipio SET latitud = '5.25', longitud = '-75.1666667' WHERE id_departamento = '17' AND id = '433'; -- MANZANARES
UPDATE municipio SET latitud = '4.8333333', longitud = '-72.3333333' WHERE id_departamento = '85' AND id = '139'; -- MANÍ
UPDATE municipio SET latitud = '3.321502', longitud = '-70.235596' WHERE id_departamento = '94' AND id = '663'; -- MAPIRIPANA
UPDATE municipio SET latitud = '2.8911111', longitud = '-72.1322222' WHERE id_departamento = '50' AND id = '325'; -- MAPIRIPÁN
UPDATE municipio SET latitud = '9.0833333', longitud = '-74.2' WHERE id_departamento = '13' AND id = '440'; -- MARGARITA
UPDATE municipio SET latitud = '6.2', longitud = '-75.3' WHERE id_departamento = '5' AND id = '440'; -- MARINILLA
UPDATE municipio SET latitud = '5.5833333', longitud = '-74.0' WHERE id_departamento = '15' AND id = '442'; -- MARIPÍ
UPDATE municipio SET latitud='5.22718', longitud='-74.89832' WHERE id='443' AND id_departamento='73'; --MARIQUITA, TOLIMA
UPDATE municipio SET latitud = '5.5', longitud = '-75.5833333' WHERE id_departamento = '17' AND id = '442'; -- MARMATO
UPDATE municipio SET latitud = '5.3333333', longitud = '-75.0' WHERE id_departamento = '17' AND id = '444'; -- MARQUETALIA
UPDATE municipio SET latitud = '4.9166667', longitud = '-75.75' WHERE id_departamento = '66' AND id = '440'; -- MARSELLA
UPDATE municipio SET latitud = '5.3333333', longitud = '-75.25' WHERE id_departamento = '17' AND id = '446'; -- MARULANDA
UPDATE municipio SET latitud = '10.0', longitud = '-75.3333333' WHERE id_departamento = '13' AND id = '442'; -- MARÍA LA BAJA
UPDATE municipio SET latitud = '7.4166667', longitud = '-73.0833333' WHERE id_departamento = '68' AND id = '444'; -- MATANZA
UPDATE municipio SET latitud = '6.2913889', longitud = '-75.5361111' WHERE id_departamento = '5' AND id = '1'; -- MEDELLÍN
UPDATE municipio SET latitud = '4.5', longitud = '-73.3333333' WHERE id_departamento = '25' AND id = '438'; -- MEDINA
UPDATE municipio SET latitud='5.9954', longitud='-76.7817' WHERE id='425' AND id_departamento='27'; --MEDIO ATRATO, CHOCÓ
UPDATE municipio SET latitud='5.14907', longitud='-76.986628' WHERE id ='430' AND id_departamento='27'; --MEDIO BAUDO, CHOCÓ
UPDATE municipio SET latitud = '5.1', longitud = '-76.6830' WHERE id_departamento = '27' AND id = '450'; -- MEDIO SAN JUAN
UPDATE municipio SET latitud = '4.25', longitud = '-74.5833333' WHERE id_departamento = '73' AND id = '449'; -- MELGAR
UPDATE municipio SET latitud = '1.75', longitud = '-77.1666667' WHERE id_departamento = '19' AND id = '450'; -- MERCADERES
UPDATE municipio SET latitud = '3.3780556', longitud = '-74.0447222' WHERE id_departamento = '50' AND id = '330'; -- MESETAS
UPDATE municipio SET latitud = '1.3483333', longitud = '-75.5116667' WHERE id_departamento = '18' AND id = '460'; -- MILÁN
UPDATE municipio SET latitud = '5.25', longitud = '-73.1666667' WHERE id_departamento = '15' AND id = '455'; -- MIRAFLORES
UPDATE municipio SET latitud = '1.3366667', longitud = '-71.9511111' WHERE id_departamento = '95' AND id = '200'; -- MIRAFLORES
UPDATE municipio SET latitud = '3.25', longitud = '-76.25' WHERE id_departamento = '19' AND id = '455'; -- MIRANDA
UPDATE municipio SET latitud='-0.73143', longitud='-71.24642' WHERE id='460' AND id_departamento='91'; --MIRITI-PARANA, AMAZONAS
UPDATE municipio SET latitud = '5.4166667', longitud = '-76.0' WHERE id_departamento = '66' AND id = '456'; -- MISTRATÓ
UPDATE municipio SET latitud = '1.1983333', longitud = '-70.1733333' WHERE id_departamento = '97' AND id = '1'; -- MITÚ
UPDATE municipio SET latitud = '1.1488889', longitud = '-76.6477778' WHERE id_departamento = '86' AND id = '1'; -- MOCOA
UPDATE municipio SET latitud = '6.5833333', longitud = '-72.9166667' WHERE id_departamento = '68' AND id = '464'; -- MOGOTES
UPDATE municipio SET latitud = '6.75', longitud = '-72.75' WHERE id_departamento = '68' AND id = '468'; -- MOLAGAVITA
UPDATE municipio SET latitud = '9.2372222', longitud = '-75.6780556' WHERE id_departamento = '23' AND id = '464'; -- MOMIL
UPDATE municipio SET latitud = '9.25', longitud = '-74.5833333' WHERE id_departamento = '13' AND id = '468'; -- MOMPÓS
UPDATE municipio SET latitud = '5.8333333', longitud = '-72.6666667' WHERE id_departamento = '15' AND id = '464'; -- MONGUA
UPDATE municipio SET latitud = '5.75', longitud = '-72.8333333' WHERE id_departamento = '15' AND id = '466'; -- MONGUÍ
UPDATE municipio SET latitud = '5.9166667', longitud = '-73.5' WHERE id_departamento = '15' AND id = '469'; -- MONIQUIRÁ
UPDATE municipio SET latitud = '5.9166667', longitud = '-75.5' WHERE id_departamento = '5' AND id = '467'; -- MONTEBELLO
UPDATE municipio SET latitud = '8.2994444', longitud = '-74.4755556' WHERE id_departamento = '13' AND id = '458'; -- MONTECRISTO
UPDATE municipio SET latitud = '7.75', longitud = '-75.6666667' WHERE id_departamento = '23' AND id = '466'; -- MONTELÍBANO
UPDATE municipio SET latitud = '4.5', longitud = '-75.8' WHERE id_departamento = '63' AND id = '470'; -- MONTENEGRO
UPDATE municipio SET latitud = '4.9166667', longitud = '-72.8333333' WHERE id_departamento = '85' AND id = '162'; -- MONTERREY
UPDATE municipio SET latitud = '8.7575', longitud = '-75.89' WHERE id_departamento = '23' AND id = '1'; -- MONTERÍA
UPDATE municipio SET latitud = '8.4166667', longitud = '-74.0' WHERE id_departamento = '13' AND id = '473'; -- MORALES
UPDATE municipio SET latitud = '2.8', longitud = '-76.6666667' WHERE id_departamento = '19' AND id = '473'; -- MORALES
UPDATE municipio SET latitud = '1.4875', longitud = '-75.725' WHERE id_departamento = '18' AND id = '479'; -- MORELIA
UPDATE municipio SET latitud='2.2392', longitud='-69.94164' WHERE id='888' AND id_departamento='94'; --MORICHAL, GUAINÍA
UPDATE municipio SET latitud = '9.4166667', longitud = '-75.3333333' WHERE id_departamento = '70' AND id = '473'; -- MORROA
UPDATE municipio SET latitud = '4.75', longitud = '-74.3333333' WHERE id_departamento = '25' AND id = '473'; -- MOSQUERA
UPDATE municipio SET latitud = '2.35', longitud = '-78.35' WHERE id_departamento = '52' AND id = '473'; -- MOSQUERA
UPDATE municipio SET latitud = '5.5797222', longitud = '-73.3713889' WHERE id_departamento = '15' AND id = '476'; -- MOTAVITA
UPDATE municipio SET latitud = '8.25', longitud = '-76.05' WHERE id_departamento = '23' AND id = '500'; -- MOÑITOS
UPDATE municipio SET latitud = '4.8769444', longitud = '-75.1738889' WHERE id_departamento = '73' AND id = '461'; -- MURILLO
UPDATE municipio SET latitud = '6.8', longitud = '-76.8' WHERE id_departamento = '5' AND id = '475'; -- MURINDÓ
UPDATE municipio SET latitud = '7.3333333', longitud = '-76.5' WHERE id_departamento = '5' AND id = '480'; -- MUTATÁ
UPDATE municipio SET latitud = '7.3333333', longitud = '-72.7166667' WHERE id_departamento = '54' AND id = '480'; -- MUTISCUA
UPDATE municipio SET latitud = '5.5166667', longitud = '-74.1166667' WHERE id_departamento = '15' AND id = '480'; -- MUZO
UPDATE municipio SET latitud = '6.7833333', longitud = '-72.6666667' WHERE id_departamento = '68' AND id = '432'; -- MÁLAGA
UPDATE municipio SET latitud = '1.5', longitud = '-78.0' WHERE id_departamento = '52' AND id = '480'; -- NARIÑO
UPDATE municipio SET latitud = '5.6', longitud = '-75.1666667' WHERE id_departamento = '5' AND id = '483'; -- NARIÑO
UPDATE municipio SET latitud = '4.45', longitud = '-74.8' WHERE id_departamento = '25' AND id = '483'; -- NARIÑO
UPDATE municipio SET latitud = '3.5833333', longitud = '-75.0833333' WHERE id_departamento = '73' AND id = '483'; -- NATAGAIMA
UPDATE municipio SET latitud = '8.0963889', longitud = '-74.7758333' WHERE id_departamento = '5' AND id = '495'; -- NECHÍ
UPDATE municipio SET latitud = '8.4238889', longitud = '-76.7911111' WHERE id_departamento = '5' AND id = '490'; -- NECOCLÍ
UPDATE municipio SET latitud = '5.1666667', longitud = '-75.5' WHERE id_departamento = '17' AND id = '486'; -- NEIRA
UPDATE municipio SET latitud = '2.9305556', longitud = '-75.3302778' WHERE id_departamento = '41' AND id = '1'; -- NEIVA
UPDATE municipio SET latitud = '5.1333333', longitud = '-73.9' WHERE id_departamento = '25' AND id = '486'; -- NEMOCÓN
UPDATE municipio SET latitud = '4.3333333', longitud = '-74.5833333' WHERE id_departamento = '25' AND id = '488'; -- NILO
UPDATE municipio SET latitud = '5.2', longitud = '-74.4166667' WHERE id_departamento = '25' AND id = '489'; -- NIMAIMA
UPDATE municipio SET latitud = '5.8333333', longitud = '-72.9166667' WHERE id_departamento = '15' AND id = '491'; -- NOBSA
UPDATE municipio SET latitud = '5.1166667', longitud = '-74.4166667' WHERE id_departamento = '25' AND id = '491'; -- NOCAIMA
UPDATE municipio SET latitud = '5.5666667', longitud = '-74.8833333' WHERE id_departamento = '17' AND id = '495'; -- NORCASIA
UPDATE municipio SET latitud = '8.5333333', longitud = '-74.0333333' WHERE id_departamento = '13' AND id = '490'; -- NOROSÍ
UPDATE municipio SET latitud = '9.8030556', longitud = '-74.3902778' WHERE id_departamento = '47' AND id = '460'; -- NUEVA GRANADA
UPDATE municipio SET latitud = '5.4166667', longitud = '-73.4166667' WHERE id_departamento = '15' AND id = '494'; -- NUEVO COLÓN
UPDATE municipio SET latitud = '5.6405556', longitud = '-72.1986111' WHERE id_departamento = '85' AND id = '225'; -- NUNCHÍA
UPDATE municipio SET latitud = '6.0', longitud = '-77.3333333' WHERE id_departamento = '27' AND id = '495'; -- NUQUÍ
UPDATE municipio SET latitud = '2.65', longitud = '-75.8' WHERE id_departamento = '41' AND id = '483'; -- NÁTAGA
UPDATE municipio SET latitud = '4.9166667', longitud = '-76.5833333' WHERE id_departamento = '27' AND id = '491'; -- NÓVITA
UPDATE municipio SET latitud = '4.5833333', longitud = '-75.9166667' WHERE id_departamento = '76' AND id = '497'; -- OBANDO
UPDATE municipio SET latitud = '6.4166667', longitud = '-73.0833333' WHERE id_departamento = '68' AND id = '498'; -- OCAMONTE
UPDATE municipio SET latitud = '8.25', longitud = '-73.3' WHERE id_departamento = '54' AND id = '498'; -- OCAÑA
UPDATE municipio SET latitud = '6.3', longitud = '-73.25' WHERE id_departamento = '68' AND id = '500'; -- OIBA
UPDATE municipio SET latitud = '5.6', longitud = '-73.3166667' WHERE id_departamento = '15' AND id = '500'; -- OICATÁ
UPDATE municipio SET latitud = '6.6666667', longitud = '-75.75' WHERE id_departamento = '5' AND id = '501'; -- OLAYA
UPDATE municipio SET latitud = '1.2666667', longitud = '-77.4833333' WHERE id_departamento = '52' AND id = '490'; -- OLAYA HERRERA
UPDATE municipio SET latitud = '6.3333333', longitud = '-72.75' WHERE id_departamento = '68' AND id = '502'; -- ONZAGA
UPDATE municipio SET latitud = '2.0833333', longitud = '-76.0166667' WHERE id_departamento = '41' AND id = '503'; -- OPORAPA
UPDATE municipio SET latitud = '0.6966667', longitud = '-76.8747222' WHERE id_departamento = '86' AND id = '320'; -- ORITO
UPDATE municipio SET latitud = '4.7941667', longitud = '-71.34' WHERE id_departamento = '85' AND id = '230'; -- OROCUÉ
UPDATE municipio SET latitud = '3.9166667', longitud = '-75.25' WHERE id_departamento = '73' AND id = '504'; -- ORTEGA
UPDATE municipio SET latitud = '1.0333333', longitud = '-77.55' WHERE id_departamento = '52' AND id = '506'; -- OSPINA
UPDATE municipio SET latitud = '5.75', longitud = '-74.25' WHERE id_departamento = '15' AND id = '507'; -- OTANCHE
UPDATE municipio SET latitud = '9.5', longitud = '-75.1666667' WHERE id_departamento = '70' AND id = '508'; -- OVEJAS
UPDATE municipio SET latitud = '5.1333333', longitud = '-73.4' WHERE id_departamento = '15' AND id = '511'; -- PACHAVITA
UPDATE municipio SET latitud = '5.25', longitud = '-74.1666667' WHERE id_departamento = '25' AND id = '513'; -- PACHO
UPDATE municipio SET latitud='-0.1207', longitud='-71.0829' WHERE id='511' AND id_departamento='97'; --PACOA, VAUPÉS
UPDATE municipio SET latitud = '3.0666667', longitud = '-76.3166667' WHERE id_departamento = '19' AND id = '513'; -- PADILLA
UPDATE municipio SET latitud = '2.45', longitud = '-75.75' WHERE id_departamento = '41' AND id = '518'; -- PAICOL
UPDATE municipio SET latitud = '8.9661111', longitud = '-73.6316667' WHERE id_departamento = '20' AND id = '517'; -- PAILITAS
UPDATE municipio SET latitud = '5.4166667', longitud = '-74.1666667' WHERE id_departamento = '25' AND id = '518'; -- PAIME
UPDATE municipio SET latitud = '5.8333333', longitud = '-73.1' WHERE id_departamento = '15' AND id = '516'; -- PAIPA
UPDATE municipio SET latitud = '5.4166667', longitud = '-72.6666667' WHERE id_departamento = '15' AND id = '518'; -- PAJARITO
UPDATE municipio SET latitud = '3.0', longitud = '-75.5' WHERE id_departamento = '41' AND id = '524'; -- PALERMO
UPDATE municipio SET latitud = '1.75', longitud = '-76.0666667' WHERE id_departamento = '41' AND id = '530'; -- PALESTINA
UPDATE municipio SET latitud = '5.0833333', longitud = '-75.6666667' WHERE id_departamento = '17' AND id = '524'; -- PALESTINA
UPDATE municipio SET latitud = '6.5833333', longitud = '-73.25' WHERE id_departamento = '68' AND id = '522'; -- PALMAR
UPDATE municipio SET latitud = '10.7466667', longitud = '-74.7555556' WHERE id_departamento = '8' AND id = '520'; -- PALMAR DE VARELA
UPDATE municipio SET latitud = '6.45', longitud = '-73.25' WHERE id_departamento = '68' AND id = '524'; -- PALMAS DEL SOCORRO
UPDATE municipio SET latitud = '3.5394444', longitud = '-76.3036111' WHERE id_departamento = '76' AND id = '520'; -- PALMIRA
UPDATE municipio SET latitud = '9.3333333', longitud = '-75.55' WHERE id_departamento = '70' AND id = '523'; -- PALMITO
UPDATE municipio SET latitud = '5.1333333', longitud = '-75.0333333' WHERE id_departamento = '73' AND id = '520'; -- PALOCABILDO
UPDATE municipio SET latitud = '7.3780556', longitud = '-72.6525' WHERE id_departamento = '54' AND id = '518'; -- PAMPLONA
UPDATE municipio SET latitud = '7.5', longitud = '-72.5833333' WHERE id_departamento = '54' AND id = '520'; -- PAMPLONITA
UPDATE municipio SET latitud='1.8646', longitud='-69.0102' WHERE id='887' AND id_departamento='94'; --PANA PANA, GUAINÍA
UPDATE municipio SET latitud='8.3096067', longitud='-81.3066246' WHERE id='1' AND id_departamento='0'; --PANAMA, EXTERIOR
UPDATE municipio SET latitud = '4.25', longitud = '-74.5' WHERE id_departamento = '25' AND id = '524'; -- PANDI
UPDATE municipio SET latitud = '6.5', longitud = '-72.4166667' WHERE id_departamento = '15' AND id = '522'; -- PANQUEBA
UPDATE municipio SET latitud='1.9105', longitud='-70.6095' WHERE id='777' AND id_departamento='97'; --PAPUNAUA, VAUPÉS
UPDATE municipio SET latitud = '4.3730556', longitud = '-73.2213889' WHERE id_departamento = '25' AND id = '530'; -- PARATEBUENO
UPDATE municipio SET latitud = '4.3333333', longitud = '-74.2833333' WHERE id_departamento = '25' AND id = '535'; -- PASCA
UPDATE municipio SET latitud = '1.21467073681653', longitud = '-77.2786474227905' WHERE id_departamento = '52' AND id = '1'; -- PASTO
UPDATE municipio SET latitud = '2.1155556', longitud = '-76.9891667' WHERE id_departamento = '19' AND id = '532'; -- PATÍA
UPDATE municipio SET latitud = '5.75', longitud = '-73.9166667' WHERE id_departamento = '15' AND id = '531'; -- PAUNA
UPDATE municipio SET latitud='5.6535', longitud='-72.3756' WHERE id= '533' AND id_departamento='15'; --PAYA, BOYACÁ
UPDATE municipio SET latitud = '5.8833333', longitud = '-71.9' WHERE id_departamento = '85' AND id = '250'; -- PAZ DE ARIPORO
UPDATE municipio SET latitud = '6.0833333', longitud = '-72.75' WHERE id_departamento = '15' AND id = '537'; -- PAZ DE RÍO
UPDATE municipio SET latitud='10.1489', longitud='-74.8379' WHERE id='541' AND id_departamento='47'; --PEDRAZA, MAGDALENA
UPDATE municipio SET latitud = '8.6916667', longitud = '-73.6663889' WHERE id_departamento = '20' AND id = '550'; -- PELAYA
UPDATE municipio SET latitud = '5.5', longitud = '-75.0833333' WHERE id_departamento = '17' AND id = '541'; -- PENSILVANIA
UPDATE municipio SET latitud = '7.0', longitud = '-75.8333333' WHERE id_departamento = '5' AND id = '543'; -- PEQUE
UPDATE municipio SET latitud = '4.8133333', longitud = '-75.6961111' WHERE id_departamento = '66' AND id = '1'; -- PEREIRA
UPDATE municipio SET latitud='-6.8699697', longitud='-75.0458515' WHERE id='4' AND id_departamento='0'; --PERU, EXTERIOR
UPDATE municipio SET latitud = '5.5833333', longitud = '-73.05' WHERE id_departamento = '15' AND id = '542'; -- PESCA
UPDATE municipio SET latitud = '6.25', longitud = '-75.25' WHERE id_departamento = '5' AND id = '541'; -- PEÑOL
UPDATE municipio SET latitud = '7.7833333', longitud = '-75.2' WHERE id_departamento = '19' AND id = '533'; -- PIAMONTE
UPDATE municipio SET latitud = '5.8383333', longitud = '-74.0366667' WHERE id_departamento = '68' AND id = '547'; -- PIEDECUESTA
UPDATE municipio SET latitud = '4.5', longitud = '-74.9166667' WHERE id_departamento = '73' AND id = '547'; -- PIEDRAS
UPDATE municipio SET latitud = '2.75', longitud = '-76.5' WHERE id_departamento = '19' AND id = '548'; -- PIENDAMÓ
UPDATE municipio SET latitud = '4.3333333', longitud = '-75.6666667' WHERE id_departamento = '63' AND id = '548'; -- PIJAO
UPDATE municipio SET latitud='9.51708', longitud='-74.193938' WHERE id ='545' AND id_departamento='47'; --PIJIÑO DEL CARMEN, MAGDALENA
UPDATE municipio SET latitud = '6.5833333', longitud = '-73.1666667' WHERE id_departamento = '68' AND id = '549'; -- PINCHOTE
UPDATE municipio SET latitud = '8.9172222', longitud = '-74.4663889' WHERE id_departamento = '13' AND id = '549'; -- PINILLOS
UPDATE municipio SET latitud = '10.75', longitud = '-75.1333333' WHERE id_departamento = '8' AND id = '549'; -- PIOJÓ
UPDATE municipio SET latitud = '5.7427778', longitud = '-72.4894444' WHERE id_departamento = '15' AND id = '550'; -- PISBA
UPDATE municipio SET latitud = '2.3333333', longitud = '-75.8333333' WHERE id_departamento = '41' AND id = '548'; -- PITAL
UPDATE municipio SET latitud = '1.75', longitud = '-76.1666667' WHERE id_departamento = '41' AND id = '551'; -- PITALITO
UPDATE municipio SET latitud = '10.4166667', longitud = '-74.3333333' WHERE id_departamento = '47' AND id = '551'; -- PIVIJAY
UPDATE municipio SET latitud = '3.25', longitud = '-75.75' WHERE id_departamento = '73' AND id = '555'; -- PLANADAS
UPDATE municipio SET latitud = '8.3333333', longitud = '-75.5833333' WHERE id_departamento = '23' AND id = '555'; -- PLANETA RICA
UPDATE municipio SET latitud = '9.8333333', longitud = '-74.3333333' WHERE id_departamento = '47' AND id = '555'; -- PLATO
UPDATE municipio SET latitud = '1.6316667', longitud = '-77.4616667' WHERE id_departamento = '52' AND id = '540'; -- POLICARPA
UPDATE municipio SET latitud = '10.75', longitud = '-74.7833333' WHERE id_departamento = '8' AND id = '558'; -- POLONUEVO
UPDATE municipio SET latitud = '10.6436111', longitud = '-74.7544444' WHERE id_departamento = '8' AND id = '560'; -- PONEDERA
UPDATE municipio SET latitud = '3.2775', longitud = '-75.6213889' WHERE id_departamento = '19' AND id = '1'; -- POPAYÁN
UPDATE municipio SET latitud = '5.6708333', longitud = '-71.93' WHERE id_departamento = '85' AND id = '263'; -- PORE
UPDATE municipio SET latitud = '0.75', longitud = '-77.4166667' WHERE id_departamento = '52' AND id = '560'; -- POTOSÍ
UPDATE municipio SET latitud = '3.4211111', longitud = '-76.2447222' WHERE id_departamento = '76' AND id = '563'; -- PRADERA
UPDATE municipio SET latitud = '3.75', longitud = '-74.8333333' WHERE id_departamento = '73' AND id = '563'; -- PRADO
UPDATE municipio SET latitud='13.3560819', longitud='-81.3733811' WHERE id='564' AND id_departamento='88'; --PROVIDENCIA, ARCHIPIÉLAGO DE SAN ANDRÉS, PROVIDENCIA Y SANTA CATALINA
UPDATE municipio SET latitud = '1.5702778', longitud = '-77.4694444' WHERE id_departamento = '52' AND id = '565'; -- PROVIDENCIA
UPDATE municipio SET latitud='-1.0778', longitud='-72.1847' WHERE id='669' AND id_departamento='91'; --PTO SANTANDER, AMAZONAS
UPDATE municipio SET latitud = '10.3666667', longitud = '-73.6333333' WHERE id_departamento = '20' AND id = '570'; -- PUEBLO BELLO
UPDATE municipio SET latitud = '8.2333333', longitud = '-74.9666667' WHERE id_departamento = '23' AND id = '570'; -- PUEBLO NUEVO
UPDATE municipio SET latitud = '5.25', longitud = '-76.1666667' WHERE id_departamento = '66' AND id = '572'; -- PUEBLO RICO
UPDATE municipio SET latitud = '5.8', longitud = '-75.85' WHERE id_departamento = '5' AND id = '576'; -- PUEBLORRICO
UPDATE municipio SET latitud = '10.9833333', longitud = '-74.3333333' WHERE id_departamento = '47' AND id = '570'; -- PUEBLOVIEJO
UPDATE municipio SET latitud = '5.9166667', longitud = '-73.5833333' WHERE id_departamento = '68' AND id = '572'; -- PUENTE NACIONAL
UPDATE municipio SET latitud = '0.9166667', longitud = '-77.25' WHERE id_departamento = '52' AND id = '573'; -- PUERRES
UPDATE municipio SET latitud='-0.95046', longitud='-73.84126' WHERE id='530' AND id_departamento='91'; --PUERTO ALEGRIA, AMAZONAS
UPDATE municipio SET latitud='-1.9336', longitud='-71.2454' WHERE id='536' AND id_departamento='91'; --PUERTO ARICA, AMAZONAS
UPDATE municipio SET latitud = '0.5158333', longitud = '-76.495' WHERE id_departamento = '86' AND id = '568'; -- PUERTO ASÍS
UPDATE municipio SET latitud = '6.4166667', longitud = '-74.5' WHERE id_departamento = '5' AND id = '579'; -- PUERTO BERRÍO
UPDATE municipio SET latitud = '5.9780556', longitud = '-74.5897222' WHERE id_departamento = '15' AND id = '572'; -- PUERTO BOYACÁ
UPDATE municipio SET latitud = '0.6838889', longitud = '-76.5858333' WHERE id_departamento = '86' AND id = '569'; -- PUERTO CAICEDO
UPDATE municipio SET latitud = '6.1877778', longitud = '-67.4730556' WHERE id_departamento = '99' AND id = '1'; -- PUERTO CARREÑO
UPDATE municipio SET latitud = '11.0166667', longitud = '-74.8833333' WHERE id_departamento = '8' AND id = '573'; -- PUERTO COLOMBIA
UPDATE municipio SET latitud='2.72704', longitud='-67.56797' WHERE id='884' AND id_departamento='94'; --PUERTO COLOMBIA, GUAINÍA
UPDATE municipio SET latitud = '2.6794444', longitud = '-72.7608333' WHERE id_departamento = '50' AND id = '450'; -- PUERTO CONCORDIA
UPDATE municipio SET latitud = '8.95', longitud = '-76.25' WHERE id_departamento = '23' AND id = '574'; -- PUERTO ESCONDIDO
UPDATE municipio SET latitud = '4.3141667', longitud = '-72.0825' WHERE id_departamento = '50' AND id = '568'; -- PUERTO GAITÁN
UPDATE municipio SET latitud = '0.9702778', longitud = '-76.5858333' WHERE id_departamento = '86' AND id = '571'; -- PUERTO GUZMÁN
UPDATE municipio SET latitud = '-0.2', longitud = '-74.7666667' WHERE id_departamento = '86' AND id = '573'; -- PUERTO LEGUÍZAMO
UPDATE municipio SET latitud = '4.6', longitud = '-74.1166667' WHERE id_departamento = '23' AND id = '580'; -- PUERTO LIBERTADOR
UPDATE municipio SET latitud = '3.0', longitud = '-72.5' WHERE id_departamento = '50' AND id = '577'; -- PUERTO LLERAS
UPDATE municipio SET latitud = '4.0833333', longitud = '-72.9666667' WHERE id_departamento = '50' AND id = '573'; -- PUERTO LÓPEZ
UPDATE municipio SET latitud = '6.1916667', longitud = '-74.5866667' WHERE id_departamento = '5' AND id = '585'; -- PUERTO NARE
UPDATE municipio SET latitud = '-3.7702778', longitud = '-70.3830556' WHERE id_departamento = '91' AND id = '540'; -- PUERTO NARIÑO
UPDATE municipio SET latitud = '6.6516667', longitud = '-74.0608333' WHERE id_departamento = '68' AND id = '573'; -- PUERTO PARRA
UPDATE municipio SET latitud = '1.9141667', longitud = '-75.145' WHERE id_departamento = '18' AND id = '592'; -- PUERTO RICO
UPDATE municipio SET latitud = '2.9383333', longitud = '-73.2083333' WHERE id_departamento = '50' AND id = '590'; -- PUERTO RICO
UPDATE municipio SET latitud = '6.2780556', longitud = '-71.1' WHERE id_departamento = '81' AND id = '591'; -- PUERTO RONDÓN
UPDATE municipio SET latitud = '5.5', longitud = '-74.5833333' WHERE id_departamento = '25' AND id = '572'; -- PUERTO SALGAR
UPDATE municipio SET latitud = '8.3636111', longitud = '-72.4075' WHERE id_departamento = '54' AND id = '553'; -- PUERTO SANTANDER
UPDATE municipio SET latitud = '3.25', longitud = '-76.4166667' WHERE id_departamento = '19' AND id = '573'; -- PUERTO TEJADA
UPDATE municipio SET latitud = '5.8708333', longitud = '-74.6455556' WHERE id_departamento = '5' AND id = '591'; -- PUERTO TRIUNFO
UPDATE municipio SET latitud = '7.5833333', longitud = '-73.6666667' WHERE id_departamento = '68' AND id = '575'; -- PUERTO WILCHES
UPDATE municipio SET latitud = '4.75', longitud = '-74.6666667' WHERE id_departamento = '25' AND id = '580'; -- PULÍ
UPDATE municipio SET latitud = '0.9166667', longitud = '-77.6666667' WHERE id_departamento = '52' AND id = '585'; -- PUPIALES
UPDATE municipio SET latitud = '2.3480556', longitud = '-76.5008333' WHERE id_departamento = '19' AND id = '585'; -- PURACE
UPDATE municipio SET latitud = '3.9166667', longitud = '-74.9166667' WHERE id_departamento = '73' AND id = '585'; -- PURIFICACIÓN
UPDATE municipio SET latitud = '9.3', longitud = '-75.6666667' WHERE id_departamento = '23' AND id = '586'; -- PURÍSIMA
UPDATE municipio SET latitud = '5.5', longitud = '-75.5' WHERE id_departamento = '17' AND id = '513'; -- PÁCORA
UPDATE municipio SET latitud = '5.1044444', longitud = '-73.0555556' WHERE id_departamento = '15' AND id = '514'; -- PÁEZ
UPDATE municipio SET latitud = '5.1044444', longitud = '-73.0555556' WHERE id_departamento = '19' AND id = '517'; -- PÁEZ
UPDATE municipio SET latitud = '6.5', longitud = '-73.1333333' WHERE id_departamento = '68' AND id = '533'; -- PÁRAMO
UPDATE municipio SET latitud = '5.15', longitud = '-74.5' WHERE id_departamento = '25' AND id = '592'; -- QUEBRADANEGRA
UPDATE municipio SET latitud = '4.3333333', longitud = '-73.8333333' WHERE id_departamento = '25' AND id = '594'; -- QUETAME
UPDATE municipio SET latitud = '5.6947222', longitud = '-76.6611111' WHERE id_departamento = '27' AND id = '1'; -- QUIBDO
UPDATE municipio SET latitud = '4.6333333', longitud = '-75.75' WHERE id_departamento = '63' AND id = '594'; -- QUIMBAYA
UPDATE municipio SET latitud = '5.3333333', longitud = '-75.6666667' WHERE id_departamento = '66' AND id = '594'; -- QUINCHÍA
UPDATE municipio SET latitud = '4.75', longitud = '-74.5833333' WHERE id_departamento = '25' AND id = '596'; -- QUIPILE
UPDATE municipio SET latitud = '5.5222222', longitud = '-74.1808333' WHERE id_departamento = '15' AND id = '580'; -- QUÍPAMA
UPDATE municipio SET latitud='4.52053', longitud='-74.594' WHERE id='599' AND id_departamento='25'; --RAFAEL REYES, CUNDINAMARCA
UPDATE municipio SET latitud = '7.5833333', longitud = '-72.5' WHERE id_departamento = '54' AND id = '599'; -- RAGONVALIA
UPDATE municipio SET latitud = '5.4166667', longitud = '-73.3333333' WHERE id_departamento = '15' AND id = '599'; -- RAMIRIQUÍ
UPDATE municipio SET latitud = '5.3333333', longitud = '-72.75' WHERE id_departamento = '85' AND id = '279'; -- RECETOR
UPDATE municipio SET latitud = '8.6666667', longitud = '-73.8333333' WHERE id_departamento = '13' AND id = '580'; -- REGIDOR
UPDATE municipio SET latitud = '7.0', longitud = '-74.4166667' WHERE id_departamento = '5' AND id = '604'; -- REMEDIOS
UPDATE municipio SET latitud = '10.6666667', longitud = '-74.5833333' WHERE id_departamento = '47' AND id = '605'; -- REMOLINO
UPDATE municipio SET latitud = '10.55', longitud = '-75.1333333' WHERE id_departamento = '8' AND id = '606'; -- REPELÓN
UPDATE municipio SET latitud = '4.1666667', longitud = '-73.4166667' WHERE id_departamento = '50' AND id = '606'; -- RESTREPO
UPDATE municipio SET latitud = '3.7833333', longitud = '-76.5' WHERE id_departamento = '76' AND id = '606'; -- RESTREPO
UPDATE municipio SET latitud = '6.0833333', longitud = '-75.5' WHERE id_departamento = '5' AND id = '607'; -- RETIRO
UPDATE municipio SET latitud = '1.1666667', longitud = '-78.1666667' WHERE id_departamento = '52' AND id = '612'; -- RICAURTE
UPDATE municipio SET latitud = '4.2797222', longitud = '-74.7761111' WHERE id_departamento = '25' AND id = '612'; -- RICAURTE
UPDATE municipio SET latitud = '5.7', longitud = '-76.6666667' WHERE id_departamento = '27' AND id = '600'; -- RIO QUITO
UPDATE municipio SET latitud = '3.5', longitud = '-75.8333333' WHERE id_departamento = '73' AND id = '616'; -- RIOBLANCO
UPDATE municipio SET latitud = '4.0833333', longitud = '-76.3333333' WHERE id_departamento = '76' AND id = '616'; -- RIOFRÍO
UPDATE municipio SET latitud = '11.5444444', longitud = '-72.9072222' WHERE id_departamento = '44' AND id = '1'; -- RIOHACHA
UPDATE municipio SET latitud = '7.2191667', longitud = '-73.1558333' WHERE id_departamento = '68' AND id = '615'; -- RIONEGRO
UPDATE municipio SET latitud = '6.1666667', longitud = '-75.4166667' WHERE id_departamento = '5' AND id = '615'; -- RIONEGRO
UPDATE municipio SET latitud = '5.3791667', longitud = '-75.6161111' WHERE id_departamento = '17' AND id = '614'; -- RIOSUCIO
UPDATE municipio SET latitud = '7.4166667', longitud = '-77.1666667' WHERE id_departamento = '27' AND id = '615'; -- RIOSUCIO
UPDATE municipio SET latitud = '5.1666667', longitud = '-75.75' WHERE id_departamento = '17' AND id = '616'; -- RISARALDA
UPDATE municipio SET latitud = '2.8333333', longitud = '-75.1666667' WHERE id_departamento = '41' AND id = '615'; -- RIVERA
UPDATE municipio SET latitud = '1.9166667', longitud = '-78.3333333' WHERE id_departamento = '52' AND id = '621'; -- ROBERTO PAYÁN
UPDATE municipio SET latitud = '4.4147222', longitud = '-76.1547222' WHERE id_departamento = '76' AND id = '622'; -- ROLDANILLO
UPDATE municipio SET latitud = '4.0833333', longitud = '-75.5833333' WHERE id_departamento = '73' AND id = '622'; -- RONCESVALLES
UPDATE municipio SET latitud = '5.4166667', longitud = '-73.1666667' WHERE id_departamento = '15' AND id = '621'; -- RONDÓN
UPDATE municipio SET latitud = '2.3333333', longitud = '-76.75' WHERE id_departamento = '19' AND id = '622'; -- ROSAS
UPDATE municipio SET latitud = '4.25', longitud = '-75.3333333' WHERE id_departamento = '73' AND id = '624'; -- ROVIRA
UPDATE municipio SET latitud = '5.6166667', longitud = '-73.6333333' WHERE id_departamento = '15' AND id = '600'; -- RÁQUIRA
UPDATE municipio SET latitud = '8.0', longitud = '-73.5' WHERE id_departamento = '20' AND id = '614'; -- RÍO DE ORO
UPDATE municipio SET latitud = '5.1', longitud = '-76.6666667' WHERE id_departamento = '27' AND id = '580'; -- RÍO IRÓ
UPDATE municipio SET latitud = '8.59', longitud = '-73.8436111' WHERE id_departamento = '13' AND id = '600'; -- RÍO VIEJO
UPDATE municipio SET latitud = '7.3980556', longitud = '-73.4969444' WHERE id_departamento = '68' AND id = '655'; -- SABANA DE TORRES
UPDATE municipio SET latitud = '10.8', longitud = '-74.75' WHERE id_departamento = '8' AND id = '634'; -- SABANAGRANDE
UPDATE municipio SET latitud = '6.9166667', longitud = '-75.8' WHERE id_departamento = '5' AND id = '628'; -- SABANALARGA
UPDATE municipio SET latitud = '10.6333333', longitud = '-74.8333333' WHERE id_departamento = '8' AND id = '638'; -- SABANALARGA
UPDATE municipio SET latitud = '4.8536111', longitud = '-73.0430556' WHERE id_departamento = '85' AND id = '300'; -- SABANALARGA
UPDATE municipio SET latitud = '10.033', longitud = '-74.217' WHERE id_departamento = '47' AND id = '660'; -- SABANAS DE SAN ANGEL
UPDATE municipio SET latitud = '6.15', longitud = '-75.6' WHERE id_departamento = '5' AND id = '631'; -- SABANETA
UPDATE municipio SET latitud = '5.75', longitud = '-73.7' WHERE id_departamento = '15' AND id = '632'; -- SABOYÁ
UPDATE municipio SET latitud = '8.8333333', longitud = '-75.4166667' WHERE id_departamento = '23' AND id = '660'; -- SAHAGÚN
UPDATE municipio SET latitud = '2.0144444', longitud = '-76.0502778' WHERE id_departamento = '41' AND id = '660'; -- SALADOBLANCO
UPDATE municipio SET latitud = '5.4166667', longitud = '-75.4166667' WHERE id_departamento = '17' AND id = '653'; -- SALAMINA
UPDATE municipio SET latitud = '10.5', longitud = '-74.7' WHERE id_departamento = '47' AND id = '675'; -- SALAMINA
UPDATE municipio SET latitud = '7.8', longitud = '-72.8333333' WHERE id_departamento = '54' AND id = '660'; -- SALAZAR
UPDATE municipio SET latitud = '3.9347222', longitud = '-75.0202778' WHERE id_departamento = '73' AND id = '671'; -- SALDAÑA
UPDATE municipio SET latitud = '4.6666667', longitud = '-75.5' WHERE id_departamento = '63' AND id = '690'; -- SALENTO
UPDATE municipio SET latitud = '6.0', longitud = '-76.0' WHERE id_departamento = '5' AND id = '642'; -- SALGAR
UPDATE municipio SET latitud = '5.5', longitud = '-73.5' WHERE id_departamento = '15' AND id = '646'; -- SAMACÁ
UPDATE municipio SET latitud = '1.4166667', longitud = '-77.6666667' WHERE id_departamento = '52' AND id = '678'; -- SAMANIEGO
UPDATE municipio SET latitud = '5.5833333', longitud = '-74.9166667' WHERE id_departamento = '17' AND id = '662'; -- SAMANÁ
UPDATE municipio SET latitud = '9.1666667', longitud = '-75.3' WHERE id_departamento = '70' AND id = '670'; -- SAMPUÉS
UPDATE municipio SET latitud = '1.9166667', longitud = '-76.3333333' WHERE id_departamento = '41' AND id = '668'; -- SAN AGUSTÍN
UPDATE municipio SET latitud = '7.7525', longitud = '-73.3891667' WHERE id_departamento = '20' AND id = '710'; -- SAN ALBERTO
UPDATE municipio SET latitud='6.906', longitud='-75.659' WHERE id='647' AND id_departamento='5'; --SAN ANDRÉS, ANTIOQUIA
UPDATE municipio SET latitud='6.8178', longitud='-72.82067' WHERE id='669' AND id_departamento='68'; --SAN ANDRÉS, SANTANDER
UPDATE municipio SET latitud='12.5831', longitud='-81.69757' WHERE id='1' AND id_departamento='88'; --SAN ANDRÉS, ARCHIPIÉLAGO DE SAN ANDRÉS, PROVIDENCIA Y SANTA CATALINA
UPDATE municipio SET latitud='9.14522', longitud='-75.509' WHERE id='670' AND id_departamento='23'; --SAN ANDRÉS SOTAVENTO, CÓRDOBA
UPDATE municipio SET latitud = '9.35', longitud = '-75.75' WHERE id_departamento = '23' AND id = '672'; -- SAN ANTERO
UPDATE municipio SET latitud = '3.9166667', longitud = '-75.5' WHERE id_departamento = '73' AND id = '675'; -- SAN ANTONIO
UPDATE municipio SET latitud='4.6161', longitud='-74.3521' WHERE id='645' AND id_departamento='25'; --SAN ANTONIO DE TEQUENDAMA, CUNDINAMARCA
UPDATE municipio SET latitud = '6.1666667', longitud = '-73.4666667' WHERE id_departamento = '68' AND id = '673'; -- SAN BENITO
UPDATE municipio SET latitud = '8.75', longitud = '-74.9166667' WHERE id_departamento = '70' AND id = '678'; -- SAN BENITO ABAD
UPDATE municipio SET latitud = '1.5163889', longitud = '-77.0466667' WHERE id_departamento = '52' AND id = '685'; -- SAN BERNARDO
UPDATE municipio SET latitud = '4.2', longitud = '-74.3333333' WHERE id_departamento = '25' AND id = '649'; -- SAN BERNARDO
UPDATE municipio SET latitud = '9.25', longitud = '-76.0' WHERE id_departamento = '23' AND id = '675'; -- SAN BERNARDO DEL VIENTO
UPDATE municipio SET latitud = '8.75', longitud = '-73.0333333' WHERE id_departamento = '54' AND id = '670'; -- SAN CALIXTO
UPDATE municipio SET latitud = '6.2', longitud = '-74.9166667' WHERE id_departamento = '5' AND id = '649'; -- SAN CARLOS
UPDATE municipio SET latitud = '8.7', longitud = '-75.7' WHERE id_departamento = '23' AND id = '678'; -- SAN CARLOS
UPDATE municipio SET latitud = '3.7122222', longitud = '-73.2436111' WHERE id_departamento = '50' AND id = '680'; -- SAN CARLOS DE GUAROA
UPDATE municipio SET latitud = '5.3333333', longitud = '-74.0833333' WHERE id_departamento = '25' AND id = '653'; -- SAN CAYETANO
UPDATE municipio SET latitud = '7.8833333', longitud = '-72.5833333' WHERE id_departamento = '54' AND id = '673'; -- SAN CAYETANO
UPDATE municipio SET latitud = '9.8833333', longitud = '-75.25' WHERE id_departamento = '13' AND id = '620'; -- SAN CRISTÓBAL
UPDATE municipio SET latitud = '10.3375', longitud = '-73.1825' WHERE id_departamento = '20' AND id = '750'; -- SAN DIEGO
UPDATE municipio SET latitud = '5.2166667', longitud = '-73.1333333' WHERE id_departamento = '15' AND id = '660'; -- SAN EDUARDO
UPDATE municipio SET latitud = '10.3983333', longitud = '-75.1511111' WHERE id_departamento = '13' AND id = '647'; -- SAN ESTANISLAO
UPDATE municipio SET latitud='1.89859', longitud='-67.07952' WHERE id='883' AND id_departamento='94'; --SAN FELIPE, GUAINÍA
UPDATE municipio SET latitud = '9.0833333', longitud = '-74.3333333' WHERE id_departamento = '13' AND id = '650'; -- SAN FERNANDO
UPDATE municipio SET latitud = '6.1166667', longitud = '-75.9833333' WHERE id_departamento = '5' AND id = '652'; -- SAN FRANCISCO
UPDATE municipio SET latitud = '1.1772222', longitud = '-76.8830556' WHERE id_departamento = '86' AND id = '755'; -- SAN FRANCISCO
UPDATE municipio SET latitud = '5.0', longitud = '-74.2833333' WHERE id_departamento = '25' AND id = '658'; -- SAN FRANCISCO
UPDATE municipio SET latitud = '6.5833333', longitud = '-73.0833333' WHERE id_departamento = '68' AND id = '679'; -- SAN GIL
UPDATE municipio SET latitud = '9.8333333', longitud = '-75.0833333' WHERE id_departamento = '13' AND id = '654'; -- SAN JACINTO
UPDATE municipio SET latitud = '9.8316667', longitud = '-75.1263889' WHERE id_departamento = '13' AND id = '655'; -- SAN JACINTO DEL CAUCA
UPDATE municipio SET latitud = '6.45', longitud = '-75.7' WHERE id_departamento = '5' AND id = '656'; -- SAN JERÓNIMO
UPDATE municipio SET latitud = '6.4333333', longitud = '-72.8666667' WHERE id_departamento = '68' AND id = '682'; -- SAN JOAQUÍN
UPDATE municipio SET latitud = '5.0858333', longitud = '-75.7883333' WHERE id_departamento = '17' AND id = '665'; -- SAN JOSÉ
UPDATE municipio SET latitud = '6.8502778', longitud = '-75.6833333' WHERE id_departamento = '5' AND id = '658'; -- SAN JOSÉ DE LA MONTAÑA
UPDATE municipio SET latitud = '6.6666667', longitud = '-72.55' WHERE id_departamento = '68' AND id = '684'; -- SAN JOSÉ DE MIRANDA
UPDATE municipio SET latitud='4.2460093', longitud='-70.320485' WHERE id='760' AND id_departamento='99'; --SAN JOSÉ DE OCUNE, VICHADA
UPDATE municipio SET latitud = '6.0833333', longitud = '-73.4666667' WHERE id_departamento = '15' AND id = '664'; -- SAN JOSÉ DE PARE
UPDATE municipio SET latitud = '7.767', longitud = '-75.517' WHERE id_departamento = '23' AND id = '682'; -- SAN JOSÉ DE URÉ
UPDATE municipio SET latitud = '1.3611111', longitud = '-75.9883333' WHERE id_departamento = '18' AND id = '610'; -- SAN JOSÉ DEL FRAGUA
UPDATE municipio SET latitud = '2.5683333', longitud = '-72.6416667' WHERE id_departamento = '95' AND id = '1'; -- SAN JOSÉ DEL GUAVIARE
UPDATE municipio SET latitud = '4.9166667', longitud = '-76.25' WHERE id_departamento = '27' AND id = '660'; -- SAN JOSÉ DEL PALMAR
UPDATE municipio SET latitud = '3.3463889', longitud = '-73.8894444' WHERE id_departamento = '50' AND id = '683'; -- SAN JUAN DE ARAMA
UPDATE municipio SET latitud = '9.2755556', longitud = '-75.2455556' WHERE id_departamento = '70' AND id = '702'; -- SAN JUAN DE BETULIA
UPDATE municipio SET latitud = '4.9166667', longitud = '-74.6666667' WHERE id_departamento = '25' AND id = '662'; -- SAN JUAN DE RÍO SECO
UPDATE municipio SET latitud = '8.7630556', longitud = '-76.53' WHERE id_departamento = '5' AND id = '659'; -- SAN JUAN DE URABÁ
UPDATE municipio SET latitud = '10.8333333', longitud = '-73.0833333' WHERE id_departamento = '44' AND id = '650'; -- SAN JUAN DEL CESAR
UPDATE municipio SET latitud = '9.9566667', longitud = '-75.0866667' WHERE id_departamento = '13' AND id = '657'; -- SAN JUAN NEPOMUCENO
UPDATE municipio SET latitud = '4.4586111', longitud = '-73.6730556' WHERE id_departamento = '50' AND id = '686'; -- SAN JUANITO
UPDATE municipio SET latitud = '1.5', longitud = '-77.1666667' WHERE id_departamento = '52' AND id = '687'; -- SAN LORENZO
UPDATE municipio SET latitud = '4.1666667', longitud = '-75.0833333' WHERE id_departamento = '73' AND id = '678'; -- SAN LUIS
UPDATE municipio SET latitud = '6.0', longitud = '-74.8333333' WHERE id_departamento = '5' AND id = '660'; -- SAN LUIS
UPDATE municipio SET latitud = '4.8213889', longitud = '-73.1675' WHERE id_departamento = '15' AND id = '667'; -- SAN LUIS DE GACENO
UPDATE municipio SET latitud = '5.2805556', longitud = '-71.5819444' WHERE id_departamento = '85' AND id = '325'; -- SAN LUIS DE PALENQUE
UPDATE municipio SET latitud = '8.5833333', longitud = '-75.1666667' WHERE id_departamento = '70' AND id = '708'; -- SAN MARCOS
UPDATE municipio SET latitud = '3.7', longitud = '-73.7' WHERE id_departamento = '50' AND id = '689'; -- SAN MARTÍN
UPDATE municipio SET latitud = '8.0047222', longitud = '-73.5152778' WHERE id_departamento = '20' AND id = '770'; -- SAN MARTÍN
UPDATE municipio SET latitud = '8.8333333', longitud = '-73.9166667' WHERE id_departamento = '13' AND id = '667'; -- SAN MARTÍN DE LOBA
UPDATE municipio SET latitud = '6.5', longitud = '-72.5' WHERE id_departamento = '15' AND id = '673'; -- SAN MATEO
UPDATE municipio SET latitud = '6.7', longitud = '-72.6666667' WHERE id_departamento = '68' AND id = '686'; -- SAN MIGUEL
UPDATE municipio SET latitud = '0.3394444', longitud = '-76.8830556' WHERE id_departamento = '86' AND id = '757'; -- SAN MIGUEL
UPDATE municipio SET latitud = '5.55', longitud = '-73.75' WHERE id_departamento = '15' AND id = '676'; -- SAN MIGUEL DE SEMA
UPDATE municipio SET latitud = '9.8333333', longitud = '-75.5' WHERE id_departamento = '70' AND id = '713'; -- SAN ONOFRE
UPDATE municipio SET latitud = '1.6666667', longitud = '-76.9166667' WHERE id_departamento = '52' AND id = '693'; -- SAN PABLO
UPDATE municipio SET latitud = '10.0527778', longitud = '-75.2680556' WHERE id_departamento = '13' AND id = '670'; -- SAN PABLO
UPDATE municipio SET latitud = '5.75', longitud = '-74.0833333' WHERE id_departamento = '15' AND id = '681'; -- SAN PABLO DE BORBUR
UPDATE municipio SET latitud = '4.0', longitud = '-76.1666667' WHERE id_departamento = '76' AND id = '670'; -- SAN PEDRO
UPDATE municipio SET latitud='6.45436', longitud='-75.57573' WHERE id='664' AND id_departamento='5'; --SAN PEDRO, ANTIOQUIA
UPDATE municipio SET latitud = '9.4166667', longitud = '-75.0' WHERE id_departamento = '70' AND id = '717'; -- SAN PEDRO
UPDATE municipio SET latitud = '1.5613889', longitud = '-77.1180556' WHERE id_departamento = '52' AND id = '694'; -- SAN PEDRO DE CARTAGO
UPDATE municipio SET latitud = '8.2761111', longitud = '-76.3786111' WHERE id_departamento = '5' AND id = '665'; -- SAN PEDRO DE URABÁ
UPDATE municipio SET latitud = '9.0', longitud = '-75.9166667' WHERE id_departamento = '23' AND id = '686'; -- SAN PELAYO
UPDATE municipio SET latitud = '6.3', longitud = '-75.0' WHERE id_departamento = '5' AND id = '667'; -- SAN RAFAEL
UPDATE municipio SET latitud = '6.4166667', longitud = '-74.8333333' WHERE id_departamento = '5' AND id = '670'; -- SAN ROQUE
UPDATE municipio SET latitud = '1.9166667', longitud = '-76.6666667' WHERE id_departamento = '19' AND id = '693'; -- SAN SEBASTIÁN
UPDATE municipio SET latitud = '9.3333333', longitud = '-74.3333333' WHERE id_departamento = '47' AND id = '692'; -- SAN SEBASTIÁN DE BUENAVISTA
UPDATE municipio SET latitud = '6.3333333', longitud = '-75.3333333' WHERE id_departamento = '5' AND id = '674'; -- SAN VICENTE
UPDATE municipio SET latitud = '6.8836111', longitud = '-73.4138889' WHERE id_departamento = '68' AND id = '689'; -- SAN VICENTE DE CHUCURÍ
UPDATE municipio SET latitud = '2.1525', longitud = '-74.7888889' WHERE id_departamento = '18' AND id = '753'; -- SAN VICENTE DEL CAGUÁN
UPDATE municipio SET latitud = '9.3333333', longitud = '-74.3' WHERE id_departamento = '47' AND id = '703'; -- SAN ZENÓN
UPDATE municipio SET latitud = '1.25', longitud = '-77.45' WHERE id_departamento = '52' AND id = '683'; -- SANDONÁ
UPDATE municipio SET latitud = '9.3166667', longitud = '-74.5833333' WHERE id_departamento = '47' AND id = '707'; -- SANTA ANA
UPDATE municipio SET latitud='1.26604', longitud='-77.40935' WHERE id='696' AND id_departamento='52'; --SANTA BARBARA, NARIÑO
UPDATE municipio SET latitud = '10.6', longitud = '-74.167' WHERE id_departamento = '47' AND id = '720'; -- SANTA BARBARA DE PINTO
UPDATE municipio SET latitud = '6.9927778', longitud = '-72.9102778' WHERE id_departamento = '68' AND id = '705'; -- SANTA BÁRBARA
UPDATE municipio SET latitud = '5.8666667', longitud = '-75.5833333' WHERE id_departamento = '5' AND id = '679'; -- SANTA BÁRBARA
UPDATE municipio SET latitud = '10.5833333', longitud = '-75.25' WHERE id_departamento = '13' AND id = '673'; -- SANTA CATALINA
UPDATE municipio SET latitud = '6.3375', longitud = '-73.5919444' WHERE id_departamento = '68' AND id = '720'; -- SANTA HELENA DEL OPÓN
UPDATE municipio SET latitud = '4.75', longitud = '-75.1666667' WHERE id_departamento = '73' AND id = '686'; -- SANTA ISABEL
UPDATE municipio SET latitud = '10.3286111', longitud = '-74.9644444' WHERE id_departamento = '8' AND id = '675'; -- SANTA LUCÍA
UPDATE municipio SET latitud = '11.2472222', longitud = '-74.2016667' WHERE id_departamento = '47' AND id = '1'; -- SANTA MARTA
UPDATE municipio SET latitud = '3.0', longitud = '-75.7' WHERE id_departamento = '41' AND id = '676'; -- SANTA MARÍA
UPDATE municipio SET latitud = '4.8616667', longitud = '-73.2641667' WHERE id_departamento = '15' AND id = '690'; -- SANTA MARÍA
UPDATE municipio SET latitud = '10.45', longitud = '-75.3333333' WHERE id_departamento = '13' AND id = '683'; -- SANTA ROSA
UPDATE municipio SET latitud = '1.5', longitud = '-76.5' WHERE id_departamento = '19' AND id = '701'; -- SANTA ROSA
UPDATE municipio SET latitud = '4.8680556', longitud = '-75.6213889' WHERE id_departamento = '66' AND id = '682'; -- SANTA ROSA DE CABAL
UPDATE municipio SET latitud = '6.6666667', longitud = '-75.4166667' WHERE id_departamento = '5' AND id = '686'; -- SANTA ROSA DE OSOS
UPDATE municipio SET latitud = '5.9166667', longitud = '-73.0' WHERE id_departamento = '15' AND id = '693'; -- SANTA ROSA DE VITERBO
UPDATE municipio SET latitud = '7.9644444', longitud = '-74.0544444' WHERE id_departamento = '13' AND id = '688'; -- SANTA ROSA DEL SUR
UPDATE municipio SET latitud = '5.1261111', longitud = '-70.8758333' WHERE id_departamento = '99' AND id = '624'; -- SANTA ROSALÍA
UPDATE municipio SET latitud = '5.75', longitud = '-73.5833333' WHERE id_departamento = '15' AND id = '696'; -- SANTA SOFÍA
UPDATE municipio SET latitud = '1.2247222', longitud = '-77.68' WHERE id_departamento = '52' AND id = '699'; -- SANTACRUZ
UPDATE municipio SET latitud = '6.5597222', longitud = '-75.8280556' WHERE id_departamento = '5' AND id = '42'; -- SANTAFÉ DE ANTIOQUIA
UPDATE municipio SET latitud = '6.0572222', longitud = '-73.4822222' WHERE id_departamento = '15' AND id = '686'; -- SANTANA
UPDATE municipio SET latitud = '3.0130556', longitud = '-76.4866667' WHERE id_departamento = '19' AND id = '698'; -- SANTANDER DE QUILICHAO
UPDATE municipio SET latitud = '1.1511111', longitud = '-77.0075' WHERE id_departamento = '86' AND id = '760'; -- SANTIAGO
UPDATE municipio SET latitud = '7.9166667', longitud = '-72.6666667' WHERE id_departamento = '54' AND id = '680'; -- SANTIAGO
UPDATE municipio SET latitud = '6.4722222', longitud = '-75.1647222' WHERE id_departamento = '5' AND id = '690'; -- SANTO DOMINGO
UPDATE municipio SET latitud = '10.75', longitud = '-74.8333333' WHERE id_departamento = '8' AND id = '685'; -- SANTO TOMÁS
UPDATE municipio SET latitud = '5.0833333', longitud = '-76.0' WHERE id_departamento = '66' AND id = '687'; -- SANTUARIO
UPDATE municipio SET latitud='6.12327', longitud='-75.26518' WHERE id='697' AND id_departamento='5'; --SANTUARIO, ANTIOQUIA
UPDATE municipio SET latitud = '1.0333333', longitud = '-77.6' WHERE id_departamento = '52' AND id = '720'; -- SAPUYES
UPDATE municipio SET latitud = '6.9205556', longitud = '-71.8533333' WHERE id_departamento = '81' AND id = '736'; -- SARAVENA
UPDATE municipio SET latitud = '8.25', longitud = '-72.75' WHERE id_departamento = '54' AND id = '720'; -- SARDINATA
UPDATE municipio SET latitud = '5.0', longitud = '-74.45' WHERE id_departamento = '25' AND id = '718'; -- SASAIMA
UPDATE municipio SET latitud = '6.1666667', longitud = '-72.6666667' WHERE id_departamento = '15' AND id = '720'; -- SATIVANORTE
UPDATE municipio SET latitud = '6.1333333', longitud = '-72.6666667' WHERE id_departamento = '15' AND id = '723'; -- SATIVASUR
UPDATE municipio SET latitud = '7.25', longitud = '-74.75' WHERE id_departamento = '5' AND id = '736'; -- SEGOVIA
UPDATE municipio SET latitud = '5.0833333', longitud = '-73.8333333' WHERE id_departamento = '25' AND id = '736'; -- SESQUILÉ
UPDATE municipio SET latitud = '4.2688889', longitud = '-75.9361111' WHERE id_departamento = '76' AND id = '736'; -- SEVILLA
UPDATE municipio SET latitud = '5.5833333', longitud = '-73.1666667' WHERE id_departamento = '15' AND id = '740'; -- SIACHOQUE
UPDATE municipio SET latitud = '4.4913889', longitud = '-74.2605556' WHERE id_departamento = '25' AND id = '740'; -- SIBATÉ
UPDATE municipio SET latitud = '1.1833333', longitud = '-76.9166667' WHERE id_departamento = '86' AND id = '749'; -- SIBUNDOY
UPDATE municipio SET latitud = '7.2', longitud = '-72.75' WHERE id_departamento = '54' AND id = '743'; -- SILOS
UPDATE municipio SET latitud = '4.5', longitud = '-74.3333333' WHERE id_departamento = '25' AND id = '743'; -- SILVANIA
UPDATE municipio SET latitud = '2.75', longitud = '-76.3333333' WHERE id_departamento = '19' AND id = '743'; -- SILVIA
UPDATE municipio SET latitud = '6.4472222', longitud = '-73.3413889' WHERE id_departamento = '68' AND id = '745'; -- SIMACOTA
UPDATE municipio SET latitud = '5.5833333', longitud = '-73.8333333' WHERE id_departamento = '25' AND id = '745'; -- SIMIJACA
UPDATE municipio SET latitud = '7.5833333', longitud = '-74.1666667' WHERE id_departamento = '13' AND id = '744'; -- SIMITÍ
UPDATE municipio SET latitud = '9.3047222', longitud = '-75.3977778' WHERE id_departamento = '70' AND id = '1'; -- SINCELEJO
UPDATE municipio SET latitud='9.2513', longitud='-75.07366' WHERE id='742' AND id_departamento='70'; --SINCE, SUCRE
UPDATE municipio SET latitud = '4.5833333', longitud = '-76.5' WHERE id_departamento = '27' AND id = '745'; -- SIPÍ
UPDATE municipio SET latitud = '10.9166667', longitud = '-74.5833333' WHERE id_departamento = '47' AND id = '745'; -- SITIONUEVO
UPDATE municipio SET latitud = '4.5872222', longitud = '-74.2213889' WHERE id_departamento = '25' AND id = '754'; -- SOACHA
UPDATE municipio SET latitud = '6.4166667', longitud = '-72.6666667' WHERE id_departamento = '15' AND id = '753'; -- SOATÁ
UPDATE municipio SET latitud = '6.0', longitud = '-72.6666667' WHERE id_departamento = '15' AND id = '757'; -- SOCHA
UPDATE municipio SET latitud = '6.5333333', longitud = '-73.2' WHERE id_departamento = '68' AND id = '755'; -- SOCORRO
UPDATE municipio SET latitud = '6.0833333', longitud = '-72.5833333' WHERE id_departamento = '15' AND id = '755'; -- SOCOTÁ
UPDATE municipio SET latitud = '5.7205556', longitud = '-72.9297222' WHERE id_departamento = '15' AND id = '759'; -- SOGAMOSO
UPDATE municipio SET latitud='0.7976478', longitud='-74.458598' WHERE id='756' AND id_departamento='18'; --SOLANO, CAQUETÁ
UPDATE municipio SET latitud = '10.9172222', longitud = '-74.7666667' WHERE id_departamento = '8' AND id = '758'; -- SOLEDAD
UPDATE municipio SET latitud = '0.9', longitud = '-75.623' WHERE id_departamento = '18' AND id = '785'; -- SOLITA
UPDATE municipio SET latitud = '4.9877778', longitud = '-73.4361111' WHERE id_departamento = '15' AND id = '761'; -- SOMONDOCO
UPDATE municipio SET latitud = '5.75', longitud = '-75.0' WHERE id_departamento = '5' AND id = '756'; -- SONSÓN
UPDATE municipio SET latitud = '6.5', longitud = '-75.75' WHERE id_departamento = '5' AND id = '761'; -- SOPETRÁN
UPDATE municipio SET latitud = '10.3333333', longitud = '-75.1' WHERE id_departamento = '13' AND id = '760'; -- SOPLAVIENTO
UPDATE municipio SET latitud = '4.9166667', longitud = '-73.95' WHERE id_departamento = '25' AND id = '758'; -- SOPÓ
UPDATE municipio SET latitud = '5.5666667', longitud = '-73.4333333' WHERE id_departamento = '15' AND id = '762'; -- SORA
UPDATE municipio SET latitud = '5.5', longitud = '-73.3166667' WHERE id_departamento = '15' AND id = '764'; -- SORACÁ
UPDATE municipio SET latitud = '5.8333333', longitud = '-73.25' WHERE id_departamento = '15' AND id = '763'; -- SOTAQUIRÁ
UPDATE municipio SET latitud = '2.2566667', longitud = '-76.6186111' WHERE id_departamento = '19' AND id = '760'; -- SOTARA
UPDATE municipio SET latitud = '6.1666667', longitud = '-73.3' WHERE id_departamento = '68' AND id = '770'; -- SUAITA
UPDATE municipio SET latitud = '10.3333333', longitud = '-74.9166667' WHERE id_departamento = '8' AND id = '770'; -- SUAN
UPDATE municipio SET latitud = '1.9166667', longitud = '-75.8333333' WHERE id_departamento = '41' AND id = '770'; -- SUAZA
UPDATE municipio SET latitud = '5.0', longitud = '-74.1666667' WHERE id_departamento = '25' AND id = '769'; -- SUBACHOQUE
UPDATE municipio SET latitud = '9.0', longitud = '-75.0' WHERE id_departamento = '70' AND id = '771'; -- SUCRE
UPDATE municipio SET latitud = '2.0333333', longitud = '-76.9166667' WHERE id_departamento = '19' AND id = '785'; -- SUCRE
UPDATE municipio SET latitud = '6.0833333', longitud = '-73.9166667' WHERE id_departamento = '68' AND id = '773'; -- SUCRE
UPDATE municipio SET latitud = '5.1666667', longitud = '-73.8333333' WHERE id_departamento = '25' AND id = '772'; -- SUESCA
UPDATE municipio SET latitud = '5.0833333', longitud = '-74.25' WHERE id_departamento = '25' AND id = '777'; -- SUPATÁ
UPDATE municipio SET latitud = '5.5', longitud = '-75.6333333' WHERE id_departamento = '17' AND id = '777'; -- SUPÍA
UPDATE municipio SET latitud = '7.5', longitud = '-72.9666667' WHERE id_departamento = '68' AND id = '780'; -- SURATÁ
UPDATE municipio SET latitud = '5.5', longitud = '-73.8333333' WHERE id_departamento = '25' AND id = '779'; -- SUSA
UPDATE municipio SET latitud = '6.2666667', longitud = '-72.6666667' WHERE id_departamento = '15' AND id = '774'; -- SUSACÓN
UPDATE municipio SET latitud = '5.6666667', longitud = '-73.5833333' WHERE id_departamento = '15' AND id = '776'; -- SUTAMARCHÁN
UPDATE municipio SET latitud = '5.251', longitud = '-73.856' WHERE id_departamento = '25' AND id = '781'; -- SUTATAUSA
UPDATE municipio SET latitud = '5.0166667', longitud = '-73.45' WHERE id_departamento = '15' AND id = '778'; -- SUTATENZA
UPDATE municipio SET latitud = '2.9588889', longitud = '-76.6952778' WHERE id_departamento = '19' AND id = '780'; -- SUÁREZ
UPDATE municipio SET latitud = '4.0833333', longitud = '-74.7833333' WHERE id_departamento = '73' AND id = '770'; -- SUÁREZ
UPDATE municipio SET latitud = '6.0833333', longitud = '-72.0833333' WHERE id_departamento = '85' AND id = '315'; -- SÁCAMA
UPDATE municipio SET latitud = '5.6666667', longitud = '-73.5' WHERE id_departamento = '15' AND id = '638'; -- SÁCHICA
UPDATE municipio SET latitud = '5.0', longitud = '-74.0833333' WHERE id_departamento = '25' AND id = '785'; -- TABIO
UPDATE municipio SET latitud = '5.3333333', longitud = '-76.4166667' WHERE id_departamento = '27' AND id = '787'; -- TADÓ
UPDATE municipio SET latitud = '9.3069444', longitud = '-74.5686111' WHERE id_departamento = '13' AND id = '780'; -- TALAIGUA NUEVO
UPDATE municipio SET latitud = '8.8333333', longitud = '-73.5833333' WHERE id_departamento = '20' AND id = '787'; -- TAMALAMEQUE
UPDATE municipio SET latitud = '6.4641667', longitud = '-71.7288889' WHERE id_departamento = '81' AND id = '794'; -- TAME
UPDATE municipio SET latitud = '1.5833333', longitud = '-77.25' WHERE id_departamento = '52' AND id = '786'; -- TAMINANGO
UPDATE municipio SET latitud = '1.0833333', longitud = '-77.3' WHERE id_departamento = '52' AND id = '788'; -- TANGUA
UPDATE municipio SET latitud = '0.4938889', longitud = '-69.6669444' WHERE id_departamento = '97' AND id = '666'; -- TARAIRA
UPDATE municipio SET latitud='-2.60706', longitud='-69.934028' WHERE id='798' AND id_departamento='91'; --TARAPACA, AMAZONAS
UPDATE municipio SET latitud = '7.5833333', longitud = '-75.35' WHERE id_departamento = '5' AND id = '790'; -- TARAZÁ
UPDATE municipio SET latitud = '2.1666667', longitud = '-75.9166667' WHERE id_departamento = '41' AND id = '791'; -- TARQUI
UPDATE municipio SET latitud = '5.8333333', longitud = '-75.8333333' WHERE id_departamento = '5' AND id = '792'; -- TARSO
UPDATE municipio SET latitud = '5.9166667', longitud = '-72.7' WHERE id_departamento = '15' AND id = '790'; -- TASCO
UPDATE municipio SET latitud = '5.0186111', longitud = '-72.7552778' WHERE id_departamento = '85' AND id = '410'; -- TAURAMENA
UPDATE municipio SET latitud = '5.25', longitud = '-73.9166667' WHERE id_departamento = '25' AND id = '793'; -- TAUSA
UPDATE municipio SET latitud = '3.0833333', longitud = '-75.0833333' WHERE id_departamento = '41' AND id = '799'; -- TELLO
UPDATE municipio SET latitud = '4.7', longitud = '-74.4166667' WHERE id_departamento = '25' AND id = '797'; -- TENA
UPDATE municipio SET latitud = '10.0', longitud = '-74.6666667' WHERE id_departamento = '47' AND id = '798'; -- TENERIFE
UPDATE municipio SET latitud = '4.9166667', longitud = '-74.1666667' WHERE id_departamento = '25' AND id = '799'; -- TENJO
UPDATE municipio SET latitud = '5.15', longitud = '-73.45' WHERE id_departamento = '15' AND id = '798'; -- TENZA
UPDATE municipio SET latitud = '8.75', longitud = '-73.1666667' WHERE id_departamento = '54' AND id = '800'; -- TEORAMA
UPDATE municipio SET latitud = '2.9166667', longitud = '-75.6666667' WHERE id_departamento = '41' AND id = '801'; -- TERUEL
UPDATE municipio SET latitud = '2.6666667', longitud = '-75.75' WHERE id_departamento = '41' AND id = '797'; -- TESALIA
UPDATE municipio SET latitud = '4.3833333', longitud = '-74.5' WHERE id_departamento = '25' AND id = '805'; -- TIBACUY
UPDATE municipio SET latitud = '5.3333333', longitud = '-73.3833333' WHERE id_departamento = '15' AND id = '804'; -- TIBANÁ
UPDATE municipio SET latitud = '5.8333333', longitud = '-72.9666667' WHERE id_departamento = '15' AND id = '806'; -- TIBASOSA
UPDATE municipio SET latitud = '5.1333333', longitud = '-73.5333333' WHERE id_departamento = '25' AND id = '807'; -- TIBIRITA
UPDATE municipio SET latitud = '8.6480556', longitud = '-72.7394444' WHERE id_departamento = '54' AND id = '810'; -- TIBÚ
UPDATE municipio SET latitud = '8.1736111', longitud = '-76.0591667' WHERE id_departamento = '23' AND id = '807'; -- TIERRALTA
UPDATE municipio SET latitud = '2.0', longitud = '-75.9166667' WHERE id_departamento = '41' AND id = '807'; -- TIMANÁ
UPDATE municipio SET latitud = '2.75', longitud = '-77.5833333' WHERE id_departamento = '19' AND id = '809'; -- TIMBIQUÍ
UPDATE municipio SET latitud = '2.4166667', longitud = '-76.75' WHERE id_departamento = '19' AND id = '807'; -- TIMBÍO
UPDATE municipio SET latitud = '5.6333333', longitud = '-73.65' WHERE id_departamento = '15' AND id = '808'; -- TINJACÁ
UPDATE municipio SET latitud = '6.4233333', longitud = '-72.6952778' WHERE id_departamento = '15' AND id = '810'; -- TIPACOQUE
UPDATE municipio SET latitud = '8.5597222', longitud = '-74.2669444' WHERE id_departamento = '13' AND id = '810'; -- TIQUISIO
UPDATE municipio SET latitud = '6.0833333', longitud = '-75.8' WHERE id_departamento = '5' AND id = '809'; -- TITIRIBÍ
UPDATE municipio SET latitud = '5.6666667', longitud = '-73.1666667' WHERE id_departamento = '15' AND id = '814'; -- TOCA
UPDATE municipio SET latitud = '4.5', longitud = '-74.6666667' WHERE id_departamento = '25' AND id = '815'; -- TOCAIMA
UPDATE municipio SET latitud = '5.0', longitud = '-73.9166667' WHERE id_departamento = '25' AND id = '817'; -- TOCANCIPÁ
UPDATE municipio SET latitud = '5.95', longitud = '-73.4166667' WHERE id_departamento = '15' AND id = '816'; -- TOGÜÍ
UPDATE municipio SET latitud = '7.0', longitud = '-75.7' WHERE id_departamento = '5' AND id = '819'; -- TOLEDO
UPDATE municipio SET latitud = '7.3', longitud = '-72.25' WHERE id_departamento = '54' AND id = '820'; -- TOLEDO
UPDATE municipio SET latitud='9.5304', longitud='-75.5303' WHERE id='820' AND id_departamento='70'; --TOLU, SUCRE
UPDATE municipio SET latitud='9.5034', longitud='-75.45031' WHERE id='823' AND id_departamento='70'; --TOLUVIEJO, SUCRE
UPDATE municipio SET latitud = '7.25', longitud = '-72.9' WHERE id_departamento = '68' AND id = '820'; -- TONA
UPDATE municipio SET latitud = '5.4166667', longitud = '-74.3333333' WHERE id_departamento = '25' AND id = '823'; -- TOPAIPÍ
UPDATE municipio SET latitud = '3.0', longitud = '-76.1666667' WHERE id_departamento = '19' AND id = '821'; -- TORIBÍO
UPDATE municipio SET latitud = '4.6116667', longitud = '-76.0813889' WHERE id_departamento = '76' AND id = '823'; -- TORO
UPDATE municipio SET latitud = '5.5', longitud = '-73.0' WHERE id_departamento = '15' AND id = '822'; -- TOTA
UPDATE municipio SET latitud = '2.5833333', longitud = '-76.3333333' WHERE id_departamento = '19' AND id = '824'; -- TOTORÓ
UPDATE municipio SET latitud = '5.4305556', longitud = '-71.6311111' WHERE id_departamento = '85' AND id = '430'; -- TRINIDAD
UPDATE municipio SET latitud = '4.25', longitud = '-76.3333333' WHERE id_departamento = '76' AND id = '828'; -- TRUJILLO
UPDATE municipio SET latitud = '10.9166667', longitud = '-74.95' WHERE id_departamento = '8' AND id = '832'; -- TUBARÁ
UPDATE municipio SET latitud = '9.1833333', longitud = '-75.55' WHERE id_departamento = '23' AND id = '815'; -- TUCHÍN
UPDATE municipio SET latitud = '4.0866667', longitud = '-76.2' WHERE id_departamento = '76' AND id = '834'; -- TULUA
UPDATE municipio SET latitud='2.42232', longitud='-78.45755' WHERE id='835' AND id_departamento='52'; --TUMACO, NARIÑO
UPDATE municipio SET latitud = '5.5352778', longitud = '-73.3677778' WHERE id_departamento = '15' AND id = '1'; -- TUNJA
UPDATE municipio SET latitud = '5.7333333', longitud = '-73.9333333' WHERE id_departamento = '15' AND id = '832'; -- TUNUNGUÁ
UPDATE municipio SET latitud = '10.35', longitud = '-75.3333333' WHERE id_departamento = '13' AND id = '836'; -- TURBACO
UPDATE municipio SET latitud = '10.25', longitud = '-75.4166667' WHERE id_departamento = '13' AND id = '838'; -- TURBANÁ
UPDATE municipio SET latitud = '8.0', longitud = '-76.5833333' WHERE id_departamento = '5' AND id = '837'; -- TURBO
UPDATE municipio SET latitud = '5.3333333', longitud = '-73.5' WHERE id_departamento = '15' AND id = '835'; -- TURMEQUÉ
UPDATE municipio SET latitud = '5.75', longitud = '-73.1666667' WHERE id_departamento = '15' AND id = '837'; -- TUTA
UPDATE municipio SET latitud = '6.1666667', longitud = '-72.8333333' WHERE id_departamento = '15' AND id = '839'; -- TUTAZÁ
UPDATE municipio SET latitud = '5.8297222', longitud = '-72.1633333' WHERE id_departamento = '85' AND id = '400'; -- TÁMARA
UPDATE municipio SET latitud = '5.6666667', longitud = '-75.6666667' WHERE id_departamento = '5' AND id = '789'; -- TÁMESIS
UPDATE municipio SET latitud = '5.8333333', longitud = '-72.8333333' WHERE id_departamento = '15' AND id = '820'; -- TÓPAGA
UPDATE municipio SET latitud = '1.1666667', longitud = '-77.5833333' WHERE id_departamento = '52' AND id = '838'; -- TÚQUERRES
UPDATE municipio SET latitud = '4.8333333', longitud = '-73.5' WHERE id_departamento = '25' AND id = '839'; -- UBALÁ
UPDATE municipio SET latitud = '4.5833333', longitud = '-74.0' WHERE id_departamento = '25' AND id = '841'; -- UBAQUE
UPDATE municipio SET latitud='5.3071', longitud='-73.81524' WHERE id='843' AND id_departamento='25'; --UBATE, CUNDINAMARCA
UPDATE municipio SET latitud = '4.7166667', longitud = '-75.75' WHERE id_departamento = '76' AND id = '845'; -- ULLOA
UPDATE municipio SET latitud = '4.3333333', longitud = '-74.0833333' WHERE id_departamento = '25' AND id = '845'; -- UNE
UPDATE municipio SET latitud = '8.0430556', longitud = '-77.0961111' WHERE id_departamento = '27' AND id = '800'; -- UNGUÍA
UPDATE municipio SET latitud = '5.283', longitud = '-76.617' WHERE id_departamento = '27' AND id = '810'; -- UNIÓN PANAMERICANA
UPDATE municipio SET latitud = '6.8994444', longitud = '-76.1741667' WHERE id_departamento = '5' AND id = '842'; -- URAMITA
UPDATE municipio SET latitud = '11.9166667', longitud = '-72.0' WHERE id_departamento = '44' AND id = '847'; -- URIBIA
UPDATE municipio SET latitud = '6.3333333', longitud = '-76.4166667' WHERE id_departamento = '5' AND id = '847'; -- URRAO
UPDATE municipio SET latitud = '10.5666667', longitud = '-73.0166667' WHERE id_departamento = '44' AND id = '855'; -- URUMITA
UPDATE municipio SET latitud = '10.75', longitud = '-74.9666667' WHERE id_departamento = '8' AND id = '849'; -- USIACURÍ
UPDATE municipio SET latitud = '7.3333333', longitud = '-75.3333333' WHERE id_departamento = '5' AND id = '854'; -- VALDIVIA
UPDATE municipio SET latitud = '8.3', longitud = '-76.1666667' WHERE id_departamento = '23' AND id = '855'; -- VALENCIA
UPDATE municipio SET latitud = '6.5', longitud = '-73.0666667' WHERE id_departamento = '68' AND id = '855'; -- VALLE DE SAN JOSÉ
UPDATE municipio SET latitud = '4.25', longitud = '-75.1666667' WHERE id_departamento = '73' AND id = '854'; -- VALLE DE SAN JUAN
UPDATE municipio SET latitud = '0.4525', longitud = '-76.9191667' WHERE id_departamento = '86' AND id = '865'; -- VALLE DEL GUAMUEZ
UPDATE municipio SET latitud = '10.4769444', longitud = '-73.2505556' WHERE id_departamento = '20' AND id = '1'; -- VALLEDUPAR
UPDATE municipio SET latitud = '5.6666667', longitud = '-75.5833333' WHERE id_departamento = '5' AND id = '856'; -- VALPARAÍSO
UPDATE municipio SET latitud = '1.1991667', longitud = '-75.7097222' WHERE id_departamento = '18' AND id = '860'; -- VALPARAÍSO
UPDATE municipio SET latitud = '6.7730556', longitud = '-74.8016667' WHERE id_departamento = '5' AND id = '858'; -- VEGACHÍ
UPDATE municipio SET latitud = '4.75', longitud = '-74.9166667' WHERE id_departamento = '73' AND id = '861'; -- VENADILLO
UPDATE municipio SET latitud = '5.9166667', longitud = '-75.75' WHERE id_departamento = '5' AND id = '861'; -- VENECIA
UPDATE municipio SET latitud='4.06375', longitud='-74.451536' WHERE id='506' AND id_departamento='25'; --VENECIA, CUNDINAMARCA
UPDATE municipio SET latitud='8.0018709', longitud='-66.1109318' WHERE id='2' AND id_departamento='0'; --VENEZUELA, EXTERIOR
UPDATE municipio SET latitud = '5.4166667', longitud = '-73.5' WHERE id_departamento = '15' AND id = '861'; -- VENTAQUEMADA
UPDATE municipio SET latitud = '5.1666667', longitud = '-74.3333333' WHERE id_departamento = '25' AND id = '862'; -- VERGARA
UPDATE municipio SET latitud = '4.6666667', longitud = '-76.25' WHERE id_departamento = '76' AND id = '863'; -- VERSALLES
UPDATE municipio SET latitud = '7.3333333', longitud = '-72.8666667' WHERE id_departamento = '68' AND id = '867'; -- VETAS
UPDATE municipio SET latitud = '4.9166667', longitud = '-74.55' WHERE id_departamento = '25' AND id = '867'; -- VIANÍ
UPDATE municipio SET latitud = '4.5238889', longitud = '-76.0411111' WHERE id_departamento = '17' AND id = '867'; -- VICTORIA
UPDATE municipio SET latitud = '6.5919444', longitud = '-76.8986111' WHERE id_departamento = '5' AND id = '873'; -- VIGÍA DEL FUERTE
UPDATE municipio SET latitud = '3.6986111', longitud = '-76.4491667' WHERE id_departamento = '76' AND id = '869'; -- VIJES
UPDATE municipio SET latitud = '7.9169444', longitud = '-72.9763889' WHERE id_departamento = '54' AND id = '871'; -- VILLA CARO
UPDATE municipio SET latitud = '5.6333333', longitud = '-73.5333333' WHERE id_departamento = '15' AND id = '407'; -- VILLA DE LEYVA
UPDATE municipio SET latitud = '7.8338889', longitud = '-72.4741667' WHERE id_departamento = '54' AND id = '874'; -- VILLA DEL ROSARIO
UPDATE municipio SET latitud = '2.5136111', longitud = '-76.8477778' WHERE id_departamento = '19' AND id = '845'; -- VILLA RICA
UPDATE municipio SET latitud = '1.038', longitud = '-76.627' WHERE id_departamento = '86' AND id = '885'; -- VILLAGARZÓN
UPDATE municipio SET latitud = '5.2761111', longitud = '-74.1988889' WHERE id_departamento = '25' AND id = '871'; -- VILLAGÓMEZ
UPDATE municipio SET latitud = '5.0', longitud = '-75.1666667' WHERE id_departamento = '73' AND id = '870'; -- VILLAHERMOSA
UPDATE municipio SET latitud = '5.0', longitud = '-75.5' WHERE id_departamento = '17' AND id = '873'; -- VILLAMARÍA
UPDATE municipio SET latitud = '10.5833333', longitud = '-73.0' WHERE id_departamento = '44' AND id = '874'; -- VILLANUEVA
UPDATE municipio SET latitud = '10.45', longitud = '-75.25' WHERE id_departamento = '13' AND id = '873'; -- VILLANUEVA
UPDATE municipio SET latitud = '6.6741667', longitud = '-73.1777778' WHERE id_departamento = '68' AND id = '872'; -- VILLANUEVA
UPDATE municipio SET latitud = '5.2833333', longitud = '-71.9666667' WHERE id_departamento = '85' AND id = '440'; -- VILLANUEVA
UPDATE municipio SET latitud = '5.2833333', longitud = '-73.5833333' WHERE id_departamento = '25' AND id = '873'; -- VILLAPINZÓN
UPDATE municipio SET latitud = '4.0', longitud = '-74.5833333' WHERE id_departamento = '73' AND id = '873'; -- VILLARRICA
UPDATE municipio SET latitud = '4.1533333', longitud = '-73.635' WHERE id_departamento = '50' AND id = '1'; -- VILLAVICENCIO
UPDATE municipio SET latitud = '3.3333333', longitud = '-75.1666667' WHERE id_departamento = '41' AND id = '872'; -- VILLAVIEJA
UPDATE municipio SET latitud = '5.0833333', longitud = '-74.5' WHERE id_departamento = '25' AND id = '875'; -- VILLETA
UPDATE municipio SET latitud = '4.5', longitud = '-74.5' WHERE id_departamento = '25' AND id = '878'; -- VIOTÁ
UPDATE municipio SET latitud = '5.5', longitud = '-73.25' WHERE id_departamento = '15' AND id = '879'; -- VIRACACHÁ
UPDATE municipio SET latitud = '3.0863889', longitud = '-73.7513889' WHERE id_departamento = '50' AND id = '711'; -- VISTAHERMOSA
UPDATE municipio SET latitud = '5.0833333', longitud = '-75.8666667' WHERE id_departamento = '17' AND id = '877'; -- VITERBO
UPDATE municipio SET latitud = '6.0833333', longitud = '-73.5833333' WHERE id_departamento = '68' AND id = '861'; -- VÉLEZ
UPDATE municipio SET latitud = '5.6666667', longitud = '-74.4166667' WHERE id_departamento = '25' AND id = '885'; -- YACOPÍ
UPDATE municipio SET latitud = '1.0833333', longitud = '-77.4166667' WHERE id_departamento = '52' AND id = '885'; -- YACUANQUER
UPDATE municipio SET latitud = '2.6666667', longitud = '-75.5833333' WHERE id_departamento = '41' AND id = '885'; -- YAGUARÁ
UPDATE municipio SET latitud = '6.8333333', longitud = '-74.75' WHERE id_departamento = '5' AND id = '885'; -- YALÍ
UPDATE municipio SET latitud = '7.0', longitud = '-75.5' WHERE id_departamento = '5' AND id = '887'; -- YARUMAL
UPDATE municipio SET latitud='0.61034', longitud='-69.20407' WHERE id='889' AND id_departamento='97'; --YAVARATE, VAUPÉS
UPDATE municipio SET latitud = '6.6666667', longitud = '-75.0' WHERE id_departamento = '5' AND id = '890'; -- YOLOMBÓ
UPDATE municipio SET latitud = '7.0077778', longitud = '-73.9141667' WHERE id_departamento = '5' AND id = '893'; -- YONDÓ
UPDATE municipio SET latitud = '5.3394444', longitud = '-72.3941667' WHERE id_departamento = '85' AND id = '1'; -- YOPAL
UPDATE municipio SET latitud = '3.9166667', longitud = '-76.3333333' WHERE id_departamento = '76' AND id = '890'; -- YOTOCO
UPDATE municipio SET latitud = '3.585', longitud = '-76.4958333' WHERE id_departamento = '76' AND id = '892'; -- YUMBO
UPDATE municipio SET latitud = '9.75', longitud = '-74.8333333' WHERE id_departamento = '13' AND id = '894'; -- ZAMBRANO
UPDATE municipio SET latitud = '6.8333333', longitud = '-73.25' WHERE id_departamento = '68' AND id = '895'; -- ZAPATOCA
UPDATE municipio SET latitud = '10.171', longitud = '-74.719' WHERE id_departamento = '47' AND id = '960'; -- ZAPAYÁN
UPDATE municipio SET latitud = '7.75', longitud = '-74.75' WHERE id_departamento = '5' AND id = '895'; -- ZARAGOZA
UPDATE municipio SET latitud = '4.3983333', longitud = '-76.0772222' WHERE id_departamento = '76' AND id = '895'; -- ZARZAL
UPDATE municipio SET latitud = '5.35', longitud = '-73.1666667' WHERE id_departamento = '15' AND id = '897'; -- ZETAQUIRA
UPDATE municipio SET latitud='4.75263', longitud='-74.37936' WHERE id='898' AND id_departamento='25'; --ZIPACON, CUNDINAMARCA
UPDATE municipio SET latitud = '5.0283333', longitud = '-74.0058333' WHERE id_departamento = '25' AND id = '899'; -- ZIPAQUIRÁ
UPDATE municipio SET latitud='10.7654', longitud='-74.1387' WHERE id='980' AND id_departamento='47'; --ZONA BANANERA, MAGDALENA
UPDATE municipio SET latitud = '8.0', longitud = '-73.2' WHERE id_departamento = '54' AND id = '3'; -- ÁBREGO
UPDATE municipio SET latitud = '2.75', longitud = '-75.75' WHERE id_departamento = '41' AND id = '357'; -- ÍQUIRA
UPDATE municipio SET latitud = '5.2333333', longitud = '-73.45' WHERE id_departamento = '15' AND id = '842'; -- ÚMBITA
UPDATE municipio SET latitud = '5.25', longitud = '-74.5' WHERE id_departamento = '25' AND id = '851'; -- ÚTICA
