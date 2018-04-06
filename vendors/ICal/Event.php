<?php

/*
 *  ####  THIS FILE IS MODIFIED FOR INTEGRATION IN HUMHUB  ####
 *  Original source can be found in subfolder
*/

namespace humhub\modules\external_calendar\vendors\ICal;

class Event
{
    const HTML_TEMPLATE = '<p>%s: %s</p>';

    /**
     * http://www.kanzaki.com/docs/ical/summary.html
     *
     * @var $summary
     */
    public $summary;

    /**
     * http://www.kanzaki.com/docs/ical/dtstart.html
     *
     * @var $dtstart
     */
    public $dtstart;

    /**
     * http://www.kanzaki.com/docs/ical/dtend.html
     *
     * @var $dtend
     */
    public $dtend;

    /**
     * http://www.kanzaki.com/docs/ical/duration.html
     *
     * @var $duration
     */
    public $duration;

    /**
     * http://www.kanzaki.com/docs/ical/dtstamp.html
     *
     * @var $dtstamp
     */
    public $dtstamp;

    /**
     * http://www.kanzaki.com/docs/ical/uid.html
     *
     * @var $uid
     */
    public $uid;

    /**
     * http://www.kanzaki.com/docs/ical/created.html
     *
     * @var $created
     */
    public $created;

    /**
     * http://www.kanzaki.com/docs/ical/lastModified.html
     *
     * @var $lastmodified
     */
    public $lastmodified;

    /**
     * http://www.kanzaki.com/docs/ical/description.html
     *
     * @var $description
     */
    public $description;

    /**
     * http://www.kanzaki.com/docs/ical/location.html
     *
     * @var $location
     */
    public $location;

    /**
     * http://www.kanzaki.com/docs/ical/sequence.html
     *
     * @var $sequence
     */
    public $sequence;

    /**
     * http://www.kanzaki.com/docs/ical/status.html
     *
     * @var $status
     */
    public $status;

    /**
     * http://www.kanzaki.com/docs/ical/transp.html
     *
     * @var $transp
     */
    public $transp;

    /**
     * http://www.kanzaki.com/docs/ical/organizer.html
     *
     * @var $organizer
     */
    public $organizer;

    /**
     * http://www.kanzaki.com/docs/ical/attendee.html
     *
     * @var $attendee
     */
    public $attendee;

    /**
     * Creates the Event object
     *
     * @param  array $data
     * @return void
     */
    public function __construct(array $data = array())
    {
        if (!empty($data)) {
            foreach ($data as $key => $value) {
                $variable = self::snakeCase($key);
                $this->{$variable} = self::prepareData($value);
            }
        }
    }

    /**
     * Prepares the data for output
     *
     * @param  mixed $value
     * @return mixed
     */
    protected function prepareData($value)
    {
        if (is_string($value)) {
            return stripslashes(trim(str_replace('\n', "\n", $value)));
        } elseif (is_array($value)) {
            return array_map('self::prepareData', $value);
        }

        return $value;
    }

    /**
     * Returns Event data excluding anything blank
     * within an HTML template
     *
     * @param  string $html HTML template to use
     * @return string
     */
    public function printData($html = self::HTML_TEMPLATE)
    {
        $data = array(
            'SUMMARY'       => $this->summary,
            'DTSTART'       => $this->dtstart,
            'DTEND'         => $this->dtend,
            'DTSTART_TZ'    => $this->dtstart_tz,
            'DTEND_TZ'      => $this->dtend_tz,
            'DURATION'      => $this->duration,
            'DTSTAMP'       => $this->dtstamp,
            'UID'           => $this->uid,
            'CREATED'       => $this->created,
            'LAST-MODIFIED' => $this->lastmodified,
            'DESCRIPTION'   => $this->description,
            'LOCATION'      => $this->location,
            'SEQUENCE'      => $this->sequence,
            'STATUS'        => $this->status,
            'TRANSP'        => $this->transp,
            'ORGANISER'     => $this->organizer,
            'ATTENDEE(S)'   => $this->attendee,
        );

        $data   = array_filter($data); // Remove any blank values
        $output = '';

        foreach ($data as $key => $value) {
            $output .= sprintf($html, $key, $value);
        }

        return $output;
    }

    /**
     * Converts the given input to snake_case
     *
     * @param  string $input
     * @param  string $glue
     * @param  string $separator
     * @return string
     */
    protected static function snakeCase($input, $glue = '_', $separator = '-')
    {
        $input = preg_split('/(?<=[a-z])(?=[A-Z])/x', $input);
        $input = join($input, $glue);
        $input = str_replace($separator, $glue, $input);

        return strtolower($input);
    }
}