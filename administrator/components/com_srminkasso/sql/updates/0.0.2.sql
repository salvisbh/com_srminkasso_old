ALTER TABLE `#__srmink_templates`
ADD COLUMN `template_key` VARCHAR(25) NULL DEFAULT NULL AFTER `image_name`,
ADD COLUMN `ausrichtung` VARCHAR(1) NULL DEFAULT 'P' AFTER `template_key`;

INSERT INTO `#__srmink_templates`
(`titel`,
`aktiv`,
`rand_links`,
`rand_rechts`,
`rand_oben`,
`rand_unten`,
`image_zeigen`,
`template_key`,
`ausrichtung`)
VALUES
(
'Fakturastatistik',
1,
20,
20,
15,
15,
0,
'billrunstatistik',
'L');
