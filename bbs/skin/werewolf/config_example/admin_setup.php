<?
	//돌연사 일반 로그를 쓰지 않은 사람을 체크한다.
	$Enable_SuddenDeath = true;

	//동일한 IP를 가진 사람은 게임에 참여하지 못하도록
	$check_ip =true;

	//운영자 도구 사용
	$useAdminTool = true;

	// 3회 이상 게임을 하면 참여할 수 있는 게임 수
	$AttandMaxCountOver3 = 1;

	// 3회 이상 게임을 하면 참여할 수 있는 게임 수
	$AttandMaxCountUnder3 = 1;

	$MaxSuddenCountUnder30M = 3;

	//최대한 만들어 질 수 있는 24시간 마을
	$H24GameMaxCount = 5;

	//최대한 만들어 질 수 있는 30분 마을
	$M30GameMaxCount = 0;

	//마을 사이의 간격
	$rangeOfTime = 1800;

	//로그 로딩 간격
	$loadingInterval = 10000;
	
	//서버가 작동 중인지 체크 (werewolf5에서만 작동 가능)
	$checkServerAlive=true;
?>