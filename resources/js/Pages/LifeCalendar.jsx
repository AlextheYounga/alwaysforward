import React from 'react';
import { useToast, Button, Flex, Box, useDisclosure } from '@chakra-ui/react';

import { RecoilRoot } from 'recoil';
import { ChakraProvider } from '@chakra-ui/react';

import WeekTimeline from '@/Components/LifeCalendar/WeekTimeline/WeekTimeline.jsx';
import DataModal from '@/Components/LifeCalendar/DataModal.jsx';
import OptionModal from '@/Components/LifeCalendar/OptionModal.jsx';
import '@/Components/LifeCalendar/LifeCalendar.module.css';

export default function LifeCalendar() {
    const toast = useToast();
    const { isOpen, onOpen, onClose } = useDisclosure();
    const [data, setData] = React.useState({
        events: [
            {
                "type": 1,
                "date": "1982-01-01",
                "title": "👶 I was born"
            },
            {
                "type": 1,
                "date": "1984-01-01",
                "title": "🎂 My 2nd birthday"
            }
        ]
    });

    React.useEffect(() => {
        let visits = parseInt(localStorage.getItem('visits') || '0');
        visits++;
        localStorage.setItem('visits', `${visits}`);
        if (visits === 1) {
            toast({
                title: 'Welcome',
                description: `- Each box is a week - Click "Events" to manage them - Works better on Desktop`,
                status: 'info',
                duration: 12000,
                isClosable: true
            });
        }
    }, []);

    return (
        <ChakraProvider resetCSS>
            <RecoilRoot>
                <div className="calendar">
                    <header className="calendar-header">
                        <Flex m={2} ml={20} justifyContent="space-between" w="100%">
                            <h1>
                                Life Calendar: Your Life in Weeks{' '}
                                <a href="http://b.link/ghub" target="_blank" rel="noreferrer" style={{ fontSize: '0.6em', color: '#555' }}>
                                    on Github
                                </a>
                            </h1>

                            <Box mr={20}>
                                <OptionModal />
                                <Button size="sm" colorScheme="teal" onClick={onOpen}>
                                    Events
                                </Button>
                            </Box>
                        </Flex>

                        <Box>
                            <WeekTimeline data={data} />
                        </Box>

                        {isOpen && (
                            <DataModal
                                dataString={JSON.stringify(data, null, 4)}
                                isOpen={isOpen}
                                onClose={onClose}
                                onSubmit={(dataJson) => {
                                    // console.log('onSubmit - dataJson', dataJson);
                                    onClose();
                                    setData(dataJson);
                                    toast({
                                        title: 'Updated',
                                        description: `You have total ${dataJson.events.length} events (boxes) now.`,
                                        status: 'success',
                                        duration: 9000,
                                        isClosable: true
                                    });
                                }}
                            />
                        )}
                    </header>
                </div>

            </RecoilRoot>
        </ChakraProvider>
    );
}
