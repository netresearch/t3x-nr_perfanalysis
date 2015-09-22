**************************
TYPO3 performance analysis
**************************

``nr_perfanalysis`` is a TYPO3 extension that collects and displays
frontend rendering timing and quantity information.

It displays the summary at the bottom right of the frontend pages:

.. figure:: doc/frontend.png
   :align: center

   TYPO3 introduction package rendering with nr_perfanalysis output

.. contents::

=====
Usage
=====
By enabling the extension, the frontend performance bar is automatically
shown whenever a TYPO3 page is generated.

Clicking on the bar hides it.


Default performance indicators
==============================
``page``
  Server-side page rendering time
``sql``
  SQL query count and time
``browser``
  Browser `rendering time`__

__ http://www.w3.org/TR/2012/REC-navigation-timing-20121217/#sec-window.performance-attribute


=================
Custom indicators
=================
Your own extensions can collect statistical data, too.

Start an event::

    $statCounter = Netresearch\NrPerfanalysis\Counter::get();
    $statCounter->start('REST', 'PUT');

Finish it off::

    $statCounter = Netresearch\NrPerfanalysis\Counter::get();
    $statCounter->finish('REST', 'PUT');

Now you logged a "PUT" event in the "REST" group, and the REST group
will show up in the statistics on the bottom right.


============
Dependencies
============
- TYPO3 6.2+
- PHP 5.4+ for page generation statistics


=====================
About nr_perfanalysis
=====================

License
=======
``nr_perfanalysis`` is licensed under the `AGPL v3`__ or later.

__ http://www.gnu.org/licenses/agpl-3.0.html


Author
======
`Christian Weiske`__, `Netresearch GmbH & Co.KG`__

__ mailto:typo3@cweiske.de
__ http://www.netresearch.de/
