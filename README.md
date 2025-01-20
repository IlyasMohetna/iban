<p align="center">
    <img src="https://raw.githubusercontent.com/ilyasmohetna/iban/main/docs/logo.svg" height="300" alt="PHP IBAN">
    <p align="center">
        <a href="https://github.com/ilyasmohetna/iban/actions"><img alt="GitHub Workflow Status (main)" src="https://github.com/ilyasmohetna/iban/actions/workflows/tests.yml/badge.svg"></a>
        <a href="https://packagist.org/packages/ilyasmohetna/iban"><img alt="Total Downloads" src="https://img.shields.io/packagist/dt/ilyasmohetna/iban"></a>
        <a href="https://packagist.org/packages/ilyasmohetna/iban"><img alt="Latest Version" src="https://img.shields.io/packagist/v/ilyasmohetna/iban"></a>
        <a href="https://packagist.org/packages/ilyasmohetna/iban"><img alt="License" src="https://img.shields.io/packagist/l/ilyasmohetna/iban"></a>
    </p>
</p>

---

This package provides a powerful and easy-to-use **PHP IBAN** utility for parsing, validating, and extracting bank details from IBANs.

> **Requires [PHP 8.0+](https://php.net/releases/)**

## Installation

Install the package using [Composer](https://getcomposer.org):

```bash
composer require ilyasmohetna/iban
```

## Features

-   ✅ Parse IBANs into structured components.
-   ✅ Validate IBAN structure and checksum.
-   ✅ Auto-detect bank details from IBANs.

## Supported Countries

|                                      Flag                                      | Country                | IBAN Validation | IBAN Parser Data | Auto Bank Detection |
| :----------------------------------------------------------------------------: | :--------------------- | :-------------: | :--------------: | :-----------------: |
|        ![Andorra](https://flagpedia.net/data/flags/icon/36x27/ad.webp)         | Andorra                |       ✅        |        ✅        |         ❌          |
|  ![United Arab Emirates](https://flagpedia.net/data/flags/icon/36x27/ae.webp)  | United Arab Emirates   |       ✅        |        ✅        |         ❌          |
|        ![Albania](https://flagpedia.net/data/flags/icon/36x27/al.webp)         | Albania                |       ✅        |        ✅        |         ❌          |
|        ![Austria](https://flagpedia.net/data/flags/icon/36x27/at.webp)         | Austria                |       ✅        |        ✅        |         ❌          |
|       ![Azerbaijan](https://flagpedia.net/data/flags/icon/36x27/az.webp)       | Azerbaijan             |       ✅        |        ✅        |         ❌          |
| ![Bosnia and Herzegovina](https://flagpedia.net/data/flags/icon/36x27/ba.webp) | Bosnia and Herzegovina |       ✅        |        ✅        |         ❌          |
|        ![Belgium](https://flagpedia.net/data/flags/icon/36x27/be.webp)         | Belgium                |       ✅        |        ✅        |         ❌          |
|        ![Bulgaria](https://flagpedia.net/data/flags/icon/36x27/bg.webp)        | Bulgaria               |       ✅        |        ✅        |         ❌          |
|        ![Bahrain](https://flagpedia.net/data/flags/icon/36x27/bh.webp)         | Bahrain                |       ✅        |        ✅        |         ❌          |
|        ![Burundi](https://flagpedia.net/data/flags/icon/36x27/bi.webp)         | Burundi                |       ✅        |        ✅        |         ❌          |
|         ![Brazil](https://flagpedia.net/data/flags/icon/36x27/br.webp)         | Brazil                 |       ✅        |        ✅        |         ❌          |
|  ![Republic of Belarus](https://flagpedia.net/data/flags/icon/36x27/by.webp)   | Republic of Belarus    |       ✅        |        ✅        |         ❌          |
|      ![Switzerland](https://flagpedia.net/data/flags/icon/36x27/ch.webp)       | Switzerland            |       ✅        |        ✅        |         ❌          |
|       ![Costa Rica](https://flagpedia.net/data/flags/icon/36x27/cr.webp)       | Costa Rica             |       ✅        |        ✅        |         ❌          |
|         ![Cyprus](https://flagpedia.net/data/flags/icon/36x27/cy.webp)         | Cyprus                 |       ✅        |        ✅        |         ❌          |
|        ![Czechia](https://flagpedia.net/data/flags/icon/36x27/cz.webp)         | Czechia                |       ✅        |        ✅        |         ❌          |
|        ![Germany](https://flagpedia.net/data/flags/icon/36x27/de.webp)         | Germany                |       ✅        |        ✅        |         ❌          |
|        ![Djibouti](https://flagpedia.net/data/flags/icon/36x27/dj.webp)        | Djibouti               |       ✅        |        ✅        |         ❌          |
|        ![Denmark](https://flagpedia.net/data/flags/icon/36x27/dk.webp)         | Denmark                |       ✅        |        ✅        |         ❌          |
|   ![Dominican Republic](https://flagpedia.net/data/flags/icon/36x27/do.webp)   | Dominican Republic     |       ✅        |        ✅        |         ❌          |
|        ![Estonia](https://flagpedia.net/data/flags/icon/36x27/ee.webp)         | Estonia                |       ✅        |        ✅        |         ❌          |
|         ![Egypt](https://flagpedia.net/data/flags/icon/36x27/eg.webp)          | Egypt                  |       ✅        |        ✅        |         ❌          |
|         ![Spain](https://flagpedia.net/data/flags/icon/36x27/es.webp)          | Spain                  |       ✅        |        ✅        |         ❌          |
|        ![Finland](https://flagpedia.net/data/flags/icon/36x27/fi.webp)         | Finland                |       ✅        |        ✅        |         ❌          |
|    ![Falkland Islands](https://flagpedia.net/data/flags/icon/36x27/fk.webp)    | Falkland Islands       |       ✅        |        ✅        |         ❌          |
|     ![Faroe Islands](https://flagpedia.net/data/flags/icon/36x27/fo.webp)      | Faroe Islands          |       ✅        |        ✅        |         ❌          |
|         ![France](https://flagpedia.net/data/flags/icon/36x27/fr.webp)         | France                 |       ✅        |        ✅        |         ✅          |
|     ![United Kingdom](https://flagpedia.net/data/flags/icon/36x27/gb.webp)     | United Kingdom         |       ✅        |        ✅        |         ❌          |
|        ![Georgia](https://flagpedia.net/data/flags/icon/36x27/ge.webp)         | Georgia                |       ✅        |        ✅        |         ❌          |
|       ![Gibraltar](https://flagpedia.net/data/flags/icon/36x27/gi.webp)        | Gibraltar              |       ✅        |        ✅        |         ❌          |
|       ![Greenland](https://flagpedia.net/data/flags/icon/36x27/gl.webp)        | Greenland              |       ✅        |        ✅        |         ❌          |
|         ![Greece](https://flagpedia.net/data/flags/icon/36x27/gr.webp)         | Greece                 |       ✅        |        ✅        |         ❌          |
|       ![Guatemala](https://flagpedia.net/data/flags/icon/36x27/gt.webp)        | Guatemala              |       ✅        |        ✅        |         ❌          |
|        ![Croatia](https://flagpedia.net/data/flags/icon/36x27/hr.webp)         | Croatia                |       ✅        |        ✅        |         ❌          |
|        ![Hungary](https://flagpedia.net/data/flags/icon/36x27/hu.webp)         | Hungary                |       ✅        |        ✅        |         ❌          |
|        ![Ireland](https://flagpedia.net/data/flags/icon/36x27/ie.webp)         | Ireland                |       ✅        |        ✅        |         ❌          |
|         ![Israel](https://flagpedia.net/data/flags/icon/36x27/il.webp)         | Israel                 |       ✅        |        ✅        |         ❌          |
|          ![Iraq](https://flagpedia.net/data/flags/icon/36x27/iq.webp)          | Iraq                   |       ✅        |        ✅        |         ❌          |
|        ![Iceland](https://flagpedia.net/data/flags/icon/36x27/is.webp)         | Iceland                |       ✅        |        ✅        |         ❌          |
|         ![Italy](https://flagpedia.net/data/flags/icon/36x27/it.webp)          | Italy                  |       ✅        |        ✅        |         ❌          |
|         ![Jordan](https://flagpedia.net/data/flags/icon/36x27/jo.webp)         | Jordan                 |       ✅        |        ✅        |         ❌          |
|         ![Kuwait](https://flagpedia.net/data/flags/icon/36x27/kw.webp)         | Kuwait                 |       ✅        |        ✅        |         ❌          |
|       ![Kazakhstan](https://flagpedia.net/data/flags/icon/36x27/kz.webp)       | Kazakhstan             |       ✅        |        ✅        |         ❌          |
|        ![Lebanon](https://flagpedia.net/data/flags/icon/36x27/lb.webp)         | Lebanon                |       ✅        |        ✅        |         ❌          |
|      ![Saint Lucia](https://flagpedia.net/data/flags/icon/36x27/lc.webp)       | Saint Lucia            |       ✅        |        ✅        |         ❌          |
|     ![Liechtenstein](https://flagpedia.net/data/flags/icon/36x27/li.webp)      | Liechtenstein          |       ✅        |        ✅        |         ❌          |
|       ![Lithuania](https://flagpedia.net/data/flags/icon/36x27/lt.webp)        | Lithuania              |       ✅        |        ✅        |         ❌          |
|       ![Luxembourg](https://flagpedia.net/data/flags/icon/36x27/lu.webp)       | Luxembourg             |       ✅        |        ✅        |         ❌          |
|         ![Latvia](https://flagpedia.net/data/flags/icon/36x27/lv.webp)         | Latvia                 |       ✅        |        ✅        |         ❌          |
|         ![Libya](https://flagpedia.net/data/flags/icon/36x27/ly.webp)          | Libya                  |       ✅        |        ✅        |         ❌          |
|         ![Monaco](https://flagpedia.net/data/flags/icon/36x27/mc.webp)         | Monaco                 |       ✅        |        ✅        |         ❌          |
|        ![Moldova](https://flagpedia.net/data/flags/icon/36x27/md.webp)         | Moldova                |       ✅        |        ✅        |         ❌          |
|       ![Montenegro](https://flagpedia.net/data/flags/icon/36x27/me.webp)       | Montenegro             |       ✅        |        ✅        |         ❌          |
|       ![Macedonia](https://flagpedia.net/data/flags/icon/36x27/mk.webp)        | Macedonia              |       ✅        |        ✅        |         ❌          |
|        ![Mongolia](https://flagpedia.net/data/flags/icon/36x27/mn.webp)        | Mongolia               |       ✅        |        ✅        |         ❌          |
|       ![Mauritania](https://flagpedia.net/data/flags/icon/36x27/mr.webp)       | Mauritania             |       ✅        |        ✅        |         ❌          |
|         ![Malta](https://flagpedia.net/data/flags/icon/36x27/mt.webp)          | Malta                  |       ✅        |        ✅        |         ❌          |
|       ![Mauritius](https://flagpedia.net/data/flags/icon/36x27/mu.webp)        | Mauritius              |       ✅        |        ✅        |         ❌          |
|       ![Nicaragua](https://flagpedia.net/data/flags/icon/36x27/ni.webp)        | Nicaragua              |       ✅        |        ✅        |         ❌          |
|      ![Netherlands](https://flagpedia.net/data/flags/icon/36x27/nl.webp)       | Netherlands            |       ✅        |        ✅        |         ❌          |
|         ![Norway](https://flagpedia.net/data/flags/icon/36x27/no.webp)         | Norway                 |       ✅        |        ✅        |         ❌          |
|        ![Pakistan](https://flagpedia.net/data/flags/icon/36x27/pk.webp)        | Pakistan               |       ✅        |        ✅        |         ❌          |
|          ![Oman](https://flagpedia.net/data/flags/icon/36x27/om.webp)          | Oman                   |       ✅        |        ✅        |         ❌          |
|         ![Poland](https://flagpedia.net/data/flags/icon/36x27/pl.webp)         | Poland                 |       ✅        |        ✅        |         ❌          |
|  ![Palestine, State of](https://flagpedia.net/data/flags/icon/36x27/ps.webp)   | Palestine, State of    |       ✅        |        ✅        |         ❌          |
|        ![Portugal](https://flagpedia.net/data/flags/icon/36x27/pt.webp)        | Portugal               |       ✅        |        ✅        |         ❌          |
|         ![Qatar](https://flagpedia.net/data/flags/icon/36x27/qa.webp)          | Qatar                  |       ✅        |        ✅        |         ❌          |
|        ![Romania](https://flagpedia.net/data/flags/icon/36x27/ro.webp)         | Romania                |       ✅        |        ✅        |         ❌          |
|         ![Serbia](https://flagpedia.net/data/flags/icon/36x27/rs.webp)         | Serbia                 |       ✅        |        ✅        |         ❌          |
|         ![Russia](https://flagpedia.net/data/flags/icon/36x27/ru.webp)         | Russia                 |       ✅        |        ✅        |         ❌          |
|      ![Saudi Arabia](https://flagpedia.net/data/flags/icon/36x27/sa.webp)      | Saudi Arabia           |       ✅        |        ✅        |         ❌          |
|       ![Seychelles](https://flagpedia.net/data/flags/icon/36x27/sc.webp)       | Seychelles             |       ✅        |        ✅        |         ❌          |
|         ![Sudan](https://flagpedia.net/data/flags/icon/36x27/sd.webp)          | Sudan                  |       ✅        |        ✅        |         ❌          |
|         ![Sweden](https://flagpedia.net/data/flags/icon/36x27/se.webp)         | Sweden                 |       ✅        |        ✅        |         ❌          |
|        ![Slovenia](https://flagpedia.net/data/flags/icon/36x27/si.webp)        | Slovenia               |       ✅        |        ✅        |         ❌          |
|        ![Slovakia](https://flagpedia.net/data/flags/icon/36x27/sk.webp)        | Slovakia               |       ✅        |        ✅        |         ❌          |
|       ![San Marino](https://flagpedia.net/data/flags/icon/36x27/sm.webp)       | San Marino             |       ✅        |        ✅        |         ❌          |
|        ![Somalia](https://flagpedia.net/data/flags/icon/36x27/so.webp)         | Somalia                |       ✅        |        ✅        |         ❌          |
| ![Sao Tome and Principe](https://flagpedia.net/data/flags/icon/36x27/st.webp)  | Sao Tome and Principe  |       ✅        |        ✅        |         ❌          |
|      ![El Salvador](https://flagpedia.net/data/flags/icon/36x27/sv.webp)       | El Salvador            |       ✅        |        ✅        |         ❌          |
|      ![Timor-Leste](https://flagpedia.net/data/flags/icon/36x27/tl.webp)       | Timor-Leste            |       ✅        |        ✅        |         ❌          |
|        ![Tunisia](https://flagpedia.net/data/flags/icon/36x27/tn.webp)         | Tunisia                |       ✅        |        ✅        |         ❌          |
|         ![Turkey](https://flagpedia.net/data/flags/icon/36x27/tr.webp)         | Turkey                 |       ✅        |        ✅        |         ❌          |
|        ![Ukraine](https://flagpedia.net/data/flags/icon/36x27/ua.webp)         | Ukraine                |       ✅        |        ✅        |         ❌          |
|   ![Vatican City State](https://flagpedia.net/data/flags/icon/36x27/va.webp)   | Vatican City State     |       ✅        |        ✅        |         ❌          |
|     ![Virgin Islands](https://flagpedia.net/data/flags/icon/36x27/vg.webp)     | Virgin Islands         |       ✅        |        ✅        |         ❌          |
|         ![Kosovo](https://flagpedia.net/data/flags/icon/36x27/xk.webp)         | Kosovo                 |       ✅        |        ✅        |         ❌          |

## Usage

### Validate an IBAN

### Parse an IBAN

## Development

### Run Code Quality Tools

🧹 Keep a modern codebase with **Pint**:

```bash
composer lint
```

✅ Run refactors using **Rector**:

```bash
composer refacto
```

⚗️ Run static analysis using **PHPStan**:

```bash
composer test:types
```

✅ Run unit tests using **PEST**:

```bash
composer test:unit
```

🚀 Run the entire test suite:

```bash
composer test
```

---

**PHP IBAN** was created by **[Ilyas Mohetna](https://github.com/ilyasmohetna)** under the **[MIT license](https://opensource.org/licenses/MIT)**.
