import pandas as pd
import os
import mysql.connector


# TODO: Task attributes
def extract_features(user_id,start_stage_id,end_stage_id,cxn):
	result = []

	# 1) Get start and end time
	query = "SELECT * FROM stages_progress WHERE user_id=%d AND stage_id=%d"%(user_id,start_stage_id)
	start_frame = pd.read_sql(query,con=cxn)
	if (len(start_frame.index)==0):
		return pd.DataFrame([])
	start_time = start_frame['created_at'].iloc[0]


	query = "SELECT * FROM stages_progress WHERE user_id=%d AND stage_id=%d"%(user_id,end_stage_id)
	end_frame = pd.read_sql(query,con=cxn)

	if (len(end_frame.index)==0):
		return pd.DataFrame([])
	end_time = end_frame['created_at'].iloc[0]

	#2) Get unique query segments
	query = "SELECT * FROM queries WHERE user_id=%d"%(user_id)
	query_frame = pd.read_sql(query,con=cxn)
	query_frame = query_frame[(query_frame['created_at'] >= start_time) & (query_frame['created_at'] <= end_time)]
	query_frame = query_frame[(query_frame['query_segment_id'] != 0)]

	query_frame['start_time'] = query_frame['created_at']
	query_frame['end_time'] = query_frame['start_time'].shift(-1)
	query_frame['end_time'].iloc[-1] = end_time
	if(len(query_frame.index) == 0):
		return pd.DataFrame([])

	query = "SELECT * FROM pages WHERE user_id=%d"%(user_id)
	page_frame = pd.read_sql(query,con=cxn)
	page_frame = page_frame[(page_frame['created_at'] >= start_time) & (page_frame['created_at'] <= end_time)]
	page_frame['start_time'] = page_frame['created_at']
	page_frame['end_time'] = page_frame['start_time'].shift(-1)
	page_frame['end_time'].iloc[-1] = end_time


	for (n,query_row) in query_frame.iterrows():
		query_start_time = query_row['start_time']
		query_end_time = query_row['end_time']
		q = query_row['query']
		page_subframe = page_frame[(page_frame['created_at'] >= query_start_time) & (page_frame['created_at'] <= query_end_time)]

		result += [{
			'segment_query_length':len(q.split(" ")),
			# 'segment_time_dwell_serp':0,
			# 'segment_time_dwell_content':0,
			# 'segment_time_percent_dwell_serp':0,
			'segment_num_content_unique':len(page_subframe.index),
			'session_num_queries':1,
			'query':q,
			# 'session_time_total':0,
			# 'session_time_dwell_serp':0,
			# 'session_time_dwell_content':0,
			# 'session_percent_time_SERPs':0,
		}]

	result = pd.DataFrame(result)
	result['session_num_queries'] = result['session_num_queries'].cumsum()
	return result

if __name__ == '__main__':
    import argparse
    parser = argparse.ArgumentParser()
    outpath = os.path.join(os.getcwd(),'data.csv')

    parser.add_argument('-v','--debug',
                            type=bool, action='store',
                            dest='debug',
                            help='debug printing',
                            default=False
                           )

    parser.add_argument('-u', '--users',
                          type=str, action='store',
                          dest='users',
                          help='user IDs',
                          required=True)
    parser.add_argument('-t', '--tasks',
                          type=str, action='store',
                          dest='tasks',
                          help='stage IDs for tasks',
                          required=True)

    parser.add_argument('-e', '--endstages',
                          type=str, action='store',
                          dest='endstages',
                          help='Stage IDs for stages immediately after task',
                          required=True)

    parser.add_argument('-d', '--database',
                          type=str, action='store',
                          dest='db',
                          help='Database name',
                          required=True)


    parser.add_argument('-p', '--password',
                          type=str, action='store',
                          dest='password',
                          help='Database password',
                          required=False)

    parser.add_argument('-n', '--username',
                          type=str, action='store',
                          dest='username',
                          help='Database username',
                          required=True)

    parser.add_argument('--hostname',
                          type=str, action='store',
                          dest='hostname',
                          help='Database hostname',
                          required=True)

    args = parser.parse_args()
    users = [int(i) for i in args.users.split(" ") if i.isdigit()]
    tasks = [int(i) for i in args.tasks.split(" ") if i.isdigit()]
    endstages = [int(i) for i in args.endstages.split(" ") if i.isdigit()]

    assert len(tasks) == len(endstages)


    cxn = mysql.connector.connect(host=args.hostname, user=args.username, password=args.password, database=args.db)

    print(users)
    print(tasks)
    frames = []
    for u in users:
    	for (t,e) in zip(tasks,endstages):
    		frames += [extract_features(u,t,e,cxn)]
    frames = pd.concat(frames)

    if args.debug:
        pd.set_option('display.max_columns', None)  # or 1000
        pd.set_option('display.max_rows', None)  # or 1000
        pd.set_option('display.max_colwidth', -1)  # or 199
        print(frame)
    frames.to_csv(outpath)
