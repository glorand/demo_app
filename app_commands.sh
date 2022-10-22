#!/bin/bash
APP_DIR=/app_dir
LOGS_DIR=/storage/logs
SAIL=./vendor/bin/sail
endSeq="\e[0m"

cd $APP_DIR

_kill_all ()
{
    clear
    printTitle 'Kill all containers'
    docker stop $(docker ps -q -a)
}

_status ()
{
    clear
    printTitle 'Docker Status'
    $SAIL ps
}

_start ()
{
    clear
    printTitle 'Docker Start'
	_kill_all && $SAIL up -d && _sh
}

_stop ()
{
    clear
    printTitle 'Docker Stop'
	$SAIL stop
}

_restart ()
{
    clear
    _stop
    _start
}

_q ()
{
    clear
    printTitle 'Start queue'
    $SAIL artisan queue:work

}

_sh ()
{
    clear
	printTitle "Docker SH"
	$SAIL shell
}

_dir ()
{
    clear
    printTitle "Dir"
    cd $APP_DIR
    exec /bin/bash
}

_tail ()
{
    clear
    printTitle "log tail"
    tail_latest  $APP_DIR$LOGS_DIR
}

_build ()
{
    clear
    printTitle 'Rebuild'
    $SAIL build --no-cache && _sh
}

function printTitle
{
    echo -e "\e[31mProject ${1}${endSeq}"
    printDivider
}

function printDivider
{
    echo -e "\e[34m--------------------${endSeq}"
}

tail_latest ()
{
  dir=${1:-.}
  for f in "$dir"/*; do
      [[ $f -nt $newest ]] && newest=$f
  done
  printTitle 'Tail file: '$newest
  tail -f "$newest"
}


for i in "$@"; do
  case $i in
    *)
      COMMAND=$i
      ;;
  esac
done

case ${COMMAND} in
    stop|STOP)
        _stop
        ;;
    start|START)
        _start
        ;;
    restart|RESTART)
        _restart
        ;;
    q|Q)
        _q
        ;;
    og|OG)
        _github
        ;;
    ot|OT)
        _trello
        ;;
    status|STATUS)
        _status
        ;;
    sh|SH)
        _sh
        ;;
    build|BUILD)
        _build
        ;;
    dir|DIR)
        _dir
        ;;
    tail|TAIL|log|LOG)
        _tail
        ;;
    short|SHORT)
        shortCodes
        ;;
    *)
        clear
        echo "==============================================================="
        echo "soho [stop | start | restart | q | status | sh | build | dir | tail or log | short]"
        echo "Docker must have already been installed."
        echo "==============================================================="

esac

exit 1
