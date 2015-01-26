<?php

        namespace Clocanth;

        class RouteGroup extends Route
        {
            public function execute()
            {
                global $ROUTE_LEVEL;

                ++$ROUTE_LEVEL;

                $result = $this->handler->__invoke();

                --$ROUTE_LEVEL;

                return $result;
            }
        }
