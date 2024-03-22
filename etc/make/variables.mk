OS_FILE =

ifeq ($(OS),Windows_NT)
	OS_FILE=-win
	ifneq ($(strip $(filter %sh,$(basename $(realpath $(SHELL))))),)
		POSIXSHELL := 1
	else
		POSIXSHELL :=
	endif
else
	POSIXSHELL := 1
endif

ifneq ($(POSIXSHELL),)
	PSEP := /
 else
	PSEP := \\
endif

INFO_PROMPT_INIT=\033[0;93m--> ""
INFO_PROMPT_END=\033[0m
