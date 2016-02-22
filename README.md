# FDACQ #
_PHP Modules for Financial Data Acquisition_

This repository provides a set of PHP modules designed to work together as a comprehensive acquisition and management system for publicly available financial data. The data is collected by a set of scripts in the "bin" directory, which can be set to run automatically. Each script requires the library of PHP classes in the "lib" directory. The data is stored in the "var" directory, as sqlite3 database files (in "db"), and as CSV files (in "csv") that can be made available to external data analysis applications.

### Naming Conventions ###

Classes are subdivided by `repository / module / database`, each of which are represented by a single character in the class prefix. All prefixes in this repository begin with "F", referring to the domain "fdacq". The second letter refers to one of the modules in the repository, and the third represents the particular dataset within the module.

### Security ID ###

Whereas security symbols are designed to be a unique set of characters, I have indexed all ticker symbols by a five-digit integer key between 10,000 and 99,999, which provides acquisition capability for up to 89,999 ticker symbols. This is a proprietary numbering system for my own data analysis purposes.

### Constraints ###

The system only currently collects data for Stocks and ETFs, though I am working on adding Currencies, Spot Commodities and Bitcoin. No plans are yet in the works for Futures, Options, Warrants or Mutual Funds.
